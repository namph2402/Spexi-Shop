@extends('components.layout')
@section('title')
    Giỏ hàng
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Giỏ hàng</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        @if (count($cart->items) > 0)
            <form action="/checkout" method="GET" id="checkout" class="row px-xl-5">
                <div class="col-lg-12 table-responsive mb-5">
                    <table class="table table-light table-borderless table-hover text-center mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:10%">Ảnh</th>
                                <th style="width:35%">Sản phẩm</th>
                                <th style="width:10%">Loại</th>
                                <th style="width:10%">Giá</th>
                                <th style="width:10%">Số lượng</th>
                                <th style="width:10%">Tổng</th>
                                <th style="width:10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @foreach ($cart->items as $item)
                                @if ($item->product && $item->warehouse)
                                    <tr>
                                        <td class="text-center">
                                            <div class="custom-control custom-radio custom-control-inline m-0" style="margin-left: 10px !important">
                                                <input type="checkbox" class="cart-item custom-control-input" id="{{ $item->id }}" value="{{ $item->id }}">
                                                <label class="custom-control-label" for="{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td><img src="{{ $item->product->image }}" style="width: 50px;"></td>
                                        <td class="text-left">
                                            <a href="{{ $item->product->full_path }}" class="text-truncate">{{ $item->product->name }}</a>
                                        </td>
                                        <td class="align-middle">
                                            {{ $item->warehouse->size->name }}, {{ $item->warehouse->color->name }}
                                        </td>
                                        <td class="unit_price align-middle">
                                            {{ number_format($item->product->sale_price, 0, '.', '.') }} đ
                                        </td>
                                        <td class="align-middle">
                                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                                <div class="input-group-btn">
                                                    <a class="btn btn-sm btn-primary btn-minus">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                </div>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" disabled
                                                    class="form-control form-control-sm bg-secondary border-0 text-center">
                                                <div class="input-group-btn">
                                                    <a class="btn btn-sm btn-primary btn-plus">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle" id="amountItem{{ $item->id }}">
                                            {{ number_format($item->product->sale_price * $item->quantity, 0, '.', '.') }} đ</td>
                                        <td class="align-middle">
                                            <a class="btn btn-sm btn-danger" style="width:30px;height:30px"
                                                href="/cart/deleteItem/{{ $item->id }}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            @php
                                $total_amount = 0;
                                foreach ($cart->items as $item) {
                                    if($item->product && $item->warehouse) {
                                        $total_amount += $item->product->sale_price * $item->quantity;
                                    }
                                }
                            @endphp
                            <tr>
                                <td colspan="6" style="font-weight:600;">
                                    <span class="text-right d-block" style="font-size:20px">Tổng tiền</span>
                                </td>
                                <td colspan="2" class="text-left" style="font-weight:700;">
                                    <span class="ml-2" id="totalAmount" style="font-size:20px">{{ number_format($total_amount, 0, '.', '.') }} đ</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12" style="text-align: right">
                    <a href="/products" class="btn btn-primary">Tiếp tục mua hàng</a>
                    <button type="submit" class="btn btn-primary ml-2 mr-5">Thanh toán</button>
                    <p class="err-cart" id="errText">Vui lòng chọn sản phẩm mua hàng</p>
                </div>
                <input type="text" hidden name="item" id="item">
            </form>
        @else
            <div class="row px-xl-5">
                <div class="col-lg-12 table-responsive mb-5">
                    <div style="text-align:center">
                        <h3 class="mb-30">Bạn chưa thêm sản phẩm nào</h3>
                        <div>
                            <a href="/products" class="btn btn-primary">Tiếp tục mua hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
