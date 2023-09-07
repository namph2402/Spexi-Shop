<?php

namespace App\Utils\Payments;


use App\Common\Config\MomoConfig;
use App\Common\Enum\OrderPaymentStatusEnum;
use App\Common\Enum\PaymentMethodEnum;
use App\Common\SingletonPattern;
use App\Models\Order;
use App\Models\PaymentMethod;

class MomoUtil extends SingletonPattern
{
    protected function __construct()
    {}

    public static function getInstance()
    {
        return parent::getInstance();
    }


    public function createRequest(Order $order, PaymentMethod $paymentMethod)
    {
        $config = new MomoConfig(json_decode($paymentMethod->config, true));

        $partnerCode = $config->getPartnerCode();
        $accessKey = $config->getAccessKey();
        $serectkey = $config->getSecretKey();
        $orderId = $order->code;
        $orderInfo = "Thanh toán cho đơn hàng $order->code tại " . config('app.name');
        $amount = $order->total_amount;
        $ipnUrl = config('app.url') . '/checkout/completed/momo';
        $redirectUrl = config('app.url') . '/checkout/completed/momo';
        $requestId = time() . "";
        $requestType = "payWithATM";
        $extraData = '';

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $serectkey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = MomoUtil::execPostRequest($config->getEndpoint(), json_encode($data));
        $jsonResult = json_decode($result, true);
        return $jsonResult['payUrl'];
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function updateIPN(array $inputData, Order $order)
    {
        $status = OrderPaymentStatusEnum::PENDING;
        $message = null;
        if ($order->payment_type != PaymentMethodEnum::MOMO) {
            new \Exception('Đơn hàng không đúng phương thức Momo');
        }
        if ($inputData['resultCode'] == '0' || $inputData['message'] == 'Successful') {
            $status = OrderPaymentStatusEnum::COMPLETED;
            $message = "Giao dịch thành công";
        }
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
