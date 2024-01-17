@extends('profile.profile')
@section('title')
    Đơn hàng
@endsection
@section('content-child')
    <div class="col-lg-9">
        <div class="row">
            <div class="col-12 p-0">
                <nav class="breadcrumb bg-light mb-3">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Thông tin đơn hàng</span>
                </nav>
            </div>
        </div>
        <div class="row bg-light">
            <div class="col-lg-12">
                <div class="p-3">
                    <div class="order-info">
                        <div class="order-detail">
                            <span class="text-user">Khách hàng:</span>
                            <li>Tên người nhận: {{ $order->customer_name }}</li>
                            <li>Số điện thoại: {{ $order->customer_phone }}</li>
                            <li>Địa chỉ nhận hàng: {{ $order->customer_text }}</li>
                            @if ($order->customer_request != null)
                                <li>Ghi chú nhận hàng: {{ $order->customer_request }}</li>
                            @endif
                        </div>
                        <div class="order-detail pt-2">
                            <span class="text-user">Đơn hàng:</span>
                            <li>Mã đơn hàng: {{ $order->code }}</li>
                            <li>Trạng thái: {{ $order->order_status }}</li>
                            <li>Thanh toán:
                                @if ($order->payment_type == 'CoD')
                                    Thanh toán khi nhận hàng
                                @else
                                    Chuyển khoản
                                @endif
                            </li>

                            <li>Ngày tạo đơn: {{ $order->created_at }}</li>
                            <table class="table table-primary table-bordered mt-2" style="background:white">
                                <thead>
                                    <tr>
                                        <th class="text-center w-10 p-2">Ảnh</th>
                                        <th class="text-center w-35 p-2">Tên sản phẩm</th>
                                        <th class="text-center w-15 p-2">Loại</th>
                                        <th class="text-center w-15 p-2">Đơn giá</th>
                                        <th class="text-center w-10 p-2">Số lượng</th>
                                        <th class="text-center w-15 p-2">Giá tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->details as $d)
                                        <tr>
                                            <td class="text-center p-2">
                                                <img class="w-100" data-src="{{ $d->product->image }}"
                                                    src="{{ $d->product->image }}" alt="{{ $d->product_name }}">
                                            </td>
                                            <td class="p-2">{{ $d->product_name }}</td>
                                            <td class="text-center p-2">{{ $d->size }}, {{ $d->color }}</td>
                                            <td class="text-right p-2">{{ number_format($d->unit_price, 0, '.', '.') }} đ
                                            </td>
                                            <td class="text-right p-2">{{ $d->quantity }}</td>
                                            <td class="text-right p-2">{{ number_format($d->amount, 0, '.', '.') }} đ</td>
                                        </tr>
                                    @endforeach
                                    @php
                                        $amount = 0;
                                        foreach ($order->details as $d) {
                                            $amount += $d->amount;
                                        }
                                    @endphp
                                    <tr style="font-size: 18px; font-weight: 500">
                                        <td class="text-right p-2" colspan="5">Thành tiền</td>
                                        <td class="text-right p-2">{{ number_format($amount, 0, '.', '.') }} đ</td>
                                    </tr>
                                    <tr style="font-size: 18px; font-weight: 500">
                                        <td class="text-right p-2" colspan="5">Phí vận chuyển</td>
                                        <td class="text-right p-2">{{ number_format($order->shipping_fee, 0, '.', '.') }} đ
                                        </td>
                                    </tr>
                                    @if ($order->discount > 0)
                                        <tr style="font-size: 18px; font-weight: 500">
                                            <td class="text-right p-2" colspan="5">Giảm giá</td>
                                            <td class="text-right p-2">{{ number_format($order->discount, 0, '.', '.') }} đ
                                            </td>
                                        </tr>
                                    @endif
                                    <tr style="font-size: 18px; font-weight: 500">
                                        <td class="text-right p-2" colspan="5">Tổng tiền</td>
                                        <td class="text-right p-2">{{ number_format($order->total_amount, 0, '.', '.') }} đ
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
