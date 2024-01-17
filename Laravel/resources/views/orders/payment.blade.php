@extends('components.layout')
@section('title')
    Đặt hàng thành công
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-12 table-responsive mb-5">
                @if ($order->payment_type == \App\Common\Enum\PaymentMethodEnum::COD)
                    <div style="text-align:center">
                        <h3 class="mb-30">Cảm ơn bạn đã đặt hàng</h3>
                        <p>Đơn hàng sẽ được gửi đi ngay sau khi chúng tôi sắp hàng xong.</p>
                        <div>
                            <a href="/products" class="btn btn-primary">Tiếp tục mua hàng</a>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <h3 class="mb-30">Cảm ơn bạn đã đặt hàng!</h3>
                        <p>Chúng tôi sẽ chuyển đến trang thanh toán theo phương thức {{ $paymentMethod->name }} sau <span
                                id="timeout">5s</span> ...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function redirectToPaymentUrl(url, updateElementId = 'timeout', timeout = 5) {
            let remainTimeout = timeout;
            setInterval(() => {
                remainTimeout--;
                document.getElementById(updateElementId).innerHTML = remainTimeout + 's';
            }, 1000);
            setTimeout(function() {
                window.location.href = url;
            }, timeout * 1000);
        }
    </script>
    @if (
        $order->payment_type == \App\Common\Enum\PaymentMethodEnum::VNPAY ||
            $order->payment_type == \App\Common\Enum\PaymentMethodEnum::MOMO)
        <script>
            redirectToPaymentUrl('{!! $paymentProcess !!}', 'timeout', 5);
        </script>
    @endif
@endsection
