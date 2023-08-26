@extends('components.layout')
@section('title')
    Đặt hàng thành công
@endsection
@section('content')
    @if ($order->payment_type == \App\Common\Enum\PaymentMethodEnum::COD)
        {{-- Thanh toan COD --}}
        <div class="check-out-tks container">
            <div class="checkrow row text-center">
                <h3>Cám ơn bạn đã đặt hàng!</h3>
                <p>Đơn hàng của bạn có mã là <b>{{ $order->code }}</b>. Đơn hàng sẽ được gửi đi ngay sau khi chúng tôi
                    sắp hàng xong.</p>
                <a class="checkout-tks-btn btn-custom" href="/products">Tiếp tục mua sắm</a>
            </div>
        </div>
    @elseif($order->payment_type == \App\Common\Enum\PaymentMethodEnum::VNPAY)
        <div class="check-out-tks container">
            <div class="checkrow row text-center">
                <h3>Cám ơn bạn đã đặt hàng!</h3>
                <p>Cám ơn bạn đã đặt hàng. Đơn hàng của bạn có mã là <b>{{ $order->code }}</b>. Chúng tôi sẽ chuyển đến
                    trang
                    thanh toán theo phương thức {{ $paymentMethod->name }} sau <span id="timeout">5s</span> ...</p>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function redirectToPaymentUrl(url, updateElementId = 'timeout', timeout = 5) {
            let remainTimeout = timeout;
            setInterval(() => {
                remainTimeout--;
                document.getElementById(updateElementId).innerHTML = remainTimeout + 's';
            }, 1000);
            setTimeout(function () {
                window.location.href = url;
            }, timeout * 1000);
        }
    </script>
    @if($order->payment_type == \App\Common\Enum\PaymentMethodEnum::VNPAY)
        <script>
            redirectToPaymentUrl('{!! $paymentProcess !!}', 'timeout', 5);
        </script>
    @endif
@endsection
