@extends('components.layout')
@section('title')
    Thanh toán
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <a class="breadcrumb-item text-dark" href="/cart">Giỏ hàng</a>
                    <span class="breadcrumb-item active">Thanh toán</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <form action="/checkout/order" class="row px-xl-5" method="POST" name="formCheckout" id="formCheckout">
            <div class="col-lg-6">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Thông tin
                        nhận hàng</span></h5>
                <div class="bg-light p-25 mb-4">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Tên khách hàng *</label>
                            <input class="form-control" type="text" name="customer_name" placeholder="Nhập tên"
                                value="{{ $profile->fullname }}">
                            <small class="err-mess d-none" id="errName">Vui lòng nhập đúng thông tin</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Số điện thoại *</label>
                            <input class="form-control" type="number" name="customer_phone"
                                placeholder="Nhập số điện thoại" value="{{ $profile->phone }}">
                            <small class="err-mess d-none" id="errPhone">Vui lòng nhập đúng thông tin</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Tỉnh/Thành phố *</label>
                            <select class="custom-select" name="province_id" id="province_id"
                                onchange="onProvinceIdChange()">
                                @if ($provinceUser != null)
                                    <option selected hidden value="{{ $provinceUser->id }}">{{ $provinceUser->name }}
                                    </option>
                                @else
                                    <option selected hidden disabled value="">Tỉnh/Thành phố</option>
                                @endif
                                @foreach ($provinces as $province)
                                    <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                @endforeach
                            </select>
                            <small class="err-mess d-none" id="errProvince">Vui lòng chọn tỉnh/thành phố</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Quận/Huyện *</label>
                            <select class="custom-select" name="district_id" id="district_id"
                                onchange="onDistrictIdChange()">
                                @if ($districtUser != null)
                                    <option selected hidden value="{{ $districtUser->id }}">{{ $districtUser->name }}
                                    </option>
                                @else
                                    <option selected hidden disabled value="">Quận/huyện</option>
                                @endif
                                @if ($provinceUser != null)
                                    @foreach ($provinceUser->districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="err-mess d-none" id="errDistrict">Vui lòng chọn quận/huyện</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Xã/Phường *</label>
                            <select class="custom-select" name="ward_id" id="ward_id" onchange="getFee(0)">
                                @if ($wardUser != null)
                                    <option selected hidden value="{{ $wardUser->id }}">{{ $wardUser->name }}</option>
                                @else
                                    <option selected hidden disabled value="">Xã/phường</option>
                                @endif
                                @if ($districtUser != null)
                                    @foreach ($districtUser->wards as $ward)
                                        <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="err-mess d-none" id="errWard">Vui lòng chọn xã/phường</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Địa chỉ chi tiết *</label>
                            <input class="form-control" type="text" name="customer_address" placeholder="Nhập địa chỉ"
                                value="{{ $profile->address }}">
                            <small class="err-mess d-none" id="errAddress">Vui lòng nhập địa chỉ</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Ghi chú tới shop</label>
                            <textarea class="form-control" name="customer_request" style="min-height: 100px"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Thông tin đơn hàng</span>
                </h5>
                <div class="bg-light p-20 mb-5">
                    <div class="border-bottom list-product">
                        <h6 class="mb-3">Sản phẩm</h6>
                        @foreach ($itemCheckout as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="img_product w-15">
                                    <img class="w-100" src="{{ $item->product->image }}"
                                        style="border: 1px solid rgb(238, 238, 238)">
                                </div>
                                <div class="name_product ml-3 mt-2">
                                    <p class="d-block item-checkout text-dark m-0">{{ $item->product->name }}</p>
                                    <p class="d-block">Xanh, Đen</p>
                                </div>
                                <div class="d-block w-20 mt-2 mr-2 text-right">
                                    <p class="d-block m-0">
                                        <small class="item-x">x</small>{{ $item->quantity }}
                                    </p>
                                    <p class="d-block">{{ number_format($item->amount, 0, '.', '.') }} đ</p>
                                </div>
                            </div>
                        @endforeach
                        <input type="text" hidden name="items" value="{{ $itemC }}">
                        <input type="text" hidden id="dataShip" value="{{ $dataItem['shipFee'] }}">
                        <input type="text" hidden id="dataDiscount" value="{{ $dataItem['discount'] }}">
                    </div>
                    <div class="border-bottom pt-4 pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Tổng tiền</h6>
                            <h6 id="amountView">{{ number_format($dataItem['total'], 0, '.', '.') }} đ</h6>
                            <input type="number" hidden name="amount" id="amount"
                                value="{{ $dataItem['total'] }}">
                            <input type="number" hidden name="amountDiscount" id="amountDiscount"
                                value="{{ $dataItem['total'] }}">
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="font-weight-medium">Phí vận chuyển</h6>
                            <h6 class="font-weight-medium" id="shippingFeeView">
                                {{ number_format($dataItem['shipFee'], 0, '.', '.') }} đ</h6>
                            <input type="number" hidden name="shipping_fee" id="shipping_fee"
                                value="{{ $dataItem['shipFee'] }}">
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="font-weight-medium">Giảm giá</h6>
                            <h6 class="font-weight-medium" id="discountView">
                                {{ number_format($dataItem['discount'], 0, '.', '.') }} đ</h6>
                            <input type="number" hidden name="discount" id="discount"
                                value="{{ $dataItem['discount'] }}">
                        </div>
                        @if (!empty($promotion))
                            <span class="d-block test promotion-text mb-3">Khuyến mãi: {{$promotion->name}} khi đơn hàng từ {{ number_format($promotion->min_order_value, 0, '.', '.') }} đ</span>
                            <input type="text" id="promotion" value="{{$promotion->type}}" hidden>
                        @else
                            <input type="text" id="promotion" value="0" hidden>
                        @endif
                        <div class="form-voucher-checkout">
                            <div class="input-group"
                                style="border: 1px solid rgb(203, 203, 203);border-radius: 5px;overflow: hidden;">
                                <input type="text" class="form-control border-0 p-20" name="voucher" id="voucher"
                                    placeholder="Mã giảm giá">
                                <input type="number" hidden name="voucherId" id="voucherId">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="btnVoucher"
                                        onclick="applyVoucher()">Áp dụng
                                    </button>
                                </div>
                            </div>
                            <small class="err-voucher d-none" id="errVoucher">Mã giảm giá không đúng</small>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Tổng thanh toán</h5>
                            <h5 id="totalAmountView">{{ number_format($dataItem['totalAll'], 0, '.', '.') }} đ</h5>
                            <input type="number" hidden name="total_amount" id="total_amount"
                                value="{{ $dataItem['totalAll'] }}">
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Thanh toán</span></h5>
                    <div class="bg-light p-30">
                        @foreach ($payment as $key=>$m)
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment_type" value="{{ $m->name }}" id="{{ $m->name }}" @if($key == 0) checked @endif>
                                <label class="custom-control-label" for="{{ $m->name }}">Thanh toán qua {{ $m->name }}</label>
                            </div>
                        </div>
                        @endforeach
                        <button class="btn btn-block btn-primary font-weight-bold py-3 mt-4" id="btnOrder" type="submit"
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
            @csrf
        </form>
    </div>
@endsection
