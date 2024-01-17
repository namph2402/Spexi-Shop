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
                                <th style="width:5%">
                                    <div class="custom-control custom-radio custom-control-inline m-0"
                                        style="margin-left: 10px !important">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <label class="custom-control-label" for="checkAll"></label>
                                    </div>
                                </th>
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
                                <tr>
                                    <td class="text-center">
                                        <div class="custom-control custom-radio custom-control-inline m-0"
                                            style="margin-left: 10px !important">
                                            <input type="checkbox" class="cart-item custom-control-input"
                                                id="{{ $item->id }}" value="{{ $item->id }}">
                                            <label class="custom-control-label" for="{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{ $item->product->image }}" style="width: 50px;">
                                    </td>
                                    <td class="text-left">
                                        <a href="{{ $item->product->full_path }}"
                                            class="text-truncate">{{ $item->product->name }}</a>
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
                                        {{ number_format($item->product->sale_price * $item->quantity, 0, '.', '.') }}
                                        đ
                                    </td>
                                    <td class="align-middle">
                                        <a class="btn btn-sm btn-danger" style="width:30px;height:30px"
                                            href="/cart/deleteItem/{{ $item->id }}">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                $total_amount = 0;
                                foreach ($cart->items as $item) {
                                    $total_amount += $item->product->sale_price * $item->quantity;
                                }
                            @endphp
                            <tr>
                                <td colspan="6" style="font-weight:600;">
                                    <span class="text-right d-block" style="font-size:20px">Tổng tiền</span>
                                </td>
                                <td colspan="2" class="text-left" style="font-weight:700;">
                                    <span class="ml-2" id="totalAmount"
                                        style="font-size:20px">{{ number_format($total_amount, 0, '.', '.') }} đ</span>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            // CheckAll
            $('#checkAll').click(function(event) {
                if (this.checked) {
                    $('.cart-item').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.cart-item').each(function() {
                        this.checked = false;
                    });
                }
            });

            // CheckItem
            $('.cart-item').click(function(event) {
                checked = $(".cart-item:checked")
                check = $(".cart-item")

                if (checked.length == check.length) {
                    document.getElementById('checkAll').checked = true;
                } else {
                    document.getElementById('checkAll').checked = false;
                }
            });

            // Checkout
            $('#checkout').on('submit', function(e) {
                var arrItem = [];
                var checkbox = document.getElementsByClassName('cart-item');
                for (var i = 0; i < checkbox.length; i++) {
                    if (checkbox[i].checked === true) {
                        arrItem.push(checkbox[i].value);
                    }
                }
                if (arrItem.length == 0) {
                    $('#errText').toggleClass("d-block");
                    e.preventDefault();
                } else {
                    document.getElementById('item').value = arrItem;
                }
            });

            // UpdateItem
            $('.quantity a').on('click', function() {
                var a = $(this);
                var oldValue = a.parent().parent().find('input').val();
                var cartId = a.parent().parent().parent().parent().find('input').val();

                if (a.hasClass('btn-plus')) {
                    var newVal = parseFloat(oldValue) + 1;
                } else {
                    if (oldValue > 0) {
                        var newVal = parseFloat(oldValue) - 1;
                    } else {
                        newVal = 0;
                    }
                }
                a.parent().parent().find('input').val(newVal);

                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: "{{ route('cart.updateItem') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: cartId,
                        quantity: newVal
                    },
                    success: function(data) {
                        if (data['data'].length == 0) {
                            location.reload();
                        } else {
                            const VND = new Intl.NumberFormat('vi-VN', {
                                tyle: 'currency',
                                currency: 'VND',
                            });
                            const amount = VND.format(data['data'].amount);
                            const totalAmount = VND.format(data['data'].totalAmount);
                            const name = 'amountItem' + `${cartId}`;
                            document.getElementById(name).innerHTML = `${amount} đ`;
                            document.getElementById("totalAmount").innerHTML =
                                `${totalAmount} đ`;
                        }
                    }
                });
            });
        })
    </script>
@endsection
