<?php

namespace App\Utils\Payments;


use App\Common\Config\VnPayConfig;
use App\Common\Enum\OrderPaymentStatusEnum;
use App\Common\Enum\PaymentMethodEnum;
use App\Common\SingletonPattern;
use App\Models\Order;
use App\Models\PaymentMethod;

class VnPayUtil extends SingletonPattern
{
    protected function __construct()
    {
    }

    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function createRequest(Order $order, PaymentMethod $paymentMethod)
    {
        $config = new VnPayConfig(json_decode($paymentMethod->config, true));

        $vnp_Url = $config->getVnpUrl();
        $vnp_Returnurl = config('app.url') . '/checkout/completed/vnpay';
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

    public function updateIPN(array $inputData, Order $order)
    {
        $status = OrderPaymentStatusEnum::PENDING;
        $message = null;
        if ($order->payment_type != PaymentMethodEnum::VNPAY) {
            new \Exception('Đơn hàng không đúng phương thức VNPAY');
        }
        if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
            $status = OrderPaymentStatusEnum::COMPLETED;
            $message = "Giao dịch thành công";
        }
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
