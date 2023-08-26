<?php

namespace App\Utils\Payments;


use App\Common\Config\VnPayConfig;
use App\Common\Enum\OrderPaymentStatusEnum;
use App\Common\Enum\PaymentMethodEnum;
use App\Common\SingletonPattern;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;

class VnPayUtil extends SingletonPattern
{

    protected $errorCode;

    protected function __construct()
    {
        $this->errorCode = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán đang bảo trì.',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];
    }

    public static function getInstance()
    {
        return parent::getInstance();
    }

    public static function responseOrderNotFoundToIPN()
    {
        $returnData = [];
        $returnData['RspCode'] = '01';
        $returnData['Message'] = 'Order not found';
        return $returnData;
    }

    public static function responseUnknownToIPN()
    {
        $returnData = [];
        $returnData['RspCode'] = '99';
        $returnData['Message'] = 'Unknow error';
        return $returnData;
    }

    public function createRequest(Order $order, PaymentMethod $paymentMethod)
    {
        $config = new VnPayConfig(json_decode($paymentMethod->config, true));

        $vnp_Url = $config->getVnpUrl();
        $vnp_Returnurl = config('app.url') . '/checkout/complete/vnpay';
        $vnp_TmnCode = $config->getVnpTmnCode();
        $vnp_HashSecret = $config->getVnpHashSecret();
        $vnp_Version = $config->getVnpVersion();
        $vnp_TxnRef = $order->code;
        $vnp_OrderInfo = "Thanh toán cho đơn hàng $vnp_TxnRef tại " . config('app.name');
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total_amount * 100;
        $vnp_Locale = $config->getVnpLocale();
        $vnp_IpAddr = request()->ip();
        $inputData = array(
            "vnp_Version" => $vnp_Version,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => 'VND',
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        $query = $this->createQuery($inputData);
        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = $this->hashData($inputData, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        return $vnp_Url;
    }

    private function createQuery(array $inputData)
    {
        $query = "";
        ksort($inputData);
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        return $query;
    }

    private function hashData(array $inputData, $vnp_HashSecret)
    {
        ksort($inputData);
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        return hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    }

    public function updateIPN(array $inputData, Order $order, PaymentMethod $paymentMethod)
    {
        $status = OrderPaymentStatusEnum::PENDING;
        $message = null;
        try {
            $config = new VnPayConfig(json_decode($paymentMethod->config, true));
            if ($order->payment_type == PaymentMethodEnum::VNPAY) {
                new \Exception('Đơn hàng không đúng phương thức VNPAY');
            }
            $message = $this->errorCode[$inputData['vnp_ResponseCode']];
            if ($this->checkSum($inputData, $config)) {
                if (isset($order)) {
                    $vnp_Amount = $inputData['vnp_Amount'] / 100;
                    if ($order->total_amount == $vnp_Amount) {
                        if ($order->status == 0) {
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                $status = OrderPaymentStatusEnum::COMPLETED;
                            } else {
                                $status = OrderPaymentStatusEnum::FAILED;
                            }
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (\Exception $e) {
            Log::error($e);
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        return [
            'status' => $status,
            'message' => $message,
            'response' => json_encode($returnData)
        ];
    }

    private function checkSum(array $inputData, VnPayConfig $config)
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        $secureHash = $this->hashData($inputData, $config->getVnpHashSecret());
        if ($secureHash == $vnp_SecureHash) {
            return true;
        }
        return false;
    }
}
