@extends('profile.profile')
@section('title')
    Kho voucher
@endsection
@section('content-child')
    <div class="col-md-9">
        <div class="row">
            <div class="col-12 p-0">
                <nav class="breadcrumb bg-light mb-3">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Kho voucher</span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 pt-2 pb-4 mb-3" style="background: white">
                <div class="row pl-2 pr-4">
                    @foreach ($vouchers as $v)
                        <div class="col-md-6 form-voucher">
                            <div class="form-voucher-content">
                                <div class="form-voucher-img">
                                    @if ($v->type == 1)
                                        <img class="w-100 h-100" src="http://localhost:8000/assets/img/order.webp">
                                        <div class="form-voucher-note">
                                            <span>Giảm hàng</span>
                                        </div>
                                    @else
                                        <img class="w-100 h-100" src="http://localhost:8000/assets/img/ship.webp">
                                        <div class="form-voucher-note">
                                            <span>Free ship</span>
                                        </div>
                                    @endif
                                    <div class="form-voucher-quantity">
                                        <span>Còn lại: {{ $v->remain_quantity }}</span>
                                    </div>
                                </div>
                                <div class="form-voucher-info">
                                    @if ($v->type == 1)
                                        <span class="d-block text-dark" style="font-size: 1.2rem">{{ $v->name }}</span>
                                    @else
                                        <span class="d-block text-dark" style="font-size: 1.2rem">Miễn phí vận
                                            chuyển</span>
                                    @endif
                                    <span class="d-block" style="font-size: 0.9rem">Áp dụng cho đơn hàng tối
                                        thiểu {{ $v->min_order_value / 1000 }}K</span>
                                    <span class="d-block" style="font-size: 0.9rem">Ngày hết hạn:
                                        {{ $v->expired_date }}</span>
                                    <span class="d-block text-code"> Mã nhập:
                                        <span>{{ $v->code }}</span></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <nav class="w-100">
                <ul class="pagination justify-content-center">
                    @if ($vouchers->previousPageUrl())
                        <li class="page-item">
                            <a class="page-link" style="cursor:pointer"
                                onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')">Trước</a>
                        </li>
                    @else
                        <li class="page-item disabled"><a class="page-link">Trước</a></li>
                    @endif
                    @for ($i = 1; $i <= $vouchers->lastPage(); $i++)
                        @if ($vouchers->currentPage() == $i)
                            <li class="page-item active">
                                <a class="page-link">{{ $i }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" style="cursor:pointer"
                                    onclick="setParamsPage('page','{{ $i }}')">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                    @if ($vouchers->nextPageUrl())
                        <li class="page-item">
                            @if ((int) Request::get('page') == 0)
                                <a class="page-link" style="cursor:pointer"
                                    onclick="setParamsPage('page','{{ (int) Request::get('page') + 2 }}')">Sau</a>
                            @else
                                <a class="page-link" style="cursor:pointer"
                                    onclick="setParamsPage('page','{{ (int) Request::get('page') + 1 }}')">Sau</a>
                            @endif
                        </li>
                    @else
                        <li class="page-item disabled"><a class="page-link">Sau</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@endsection
