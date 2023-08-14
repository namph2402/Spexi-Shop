@extends('components.layout')
@section('title')
    @yield('title')
@endsection
@section('content')
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-3" style="padding-top: 4px">
                <ul class="list-info">
                    <li class="list-info-item"><a class="list-info-item-link" href="/profile"><i
                                class="fas fa-user text-primary pr-3"></i>Thông tin</a></li>
                    <li class="list-info-item"><a class="list-info-item-link" href="/order"><i
                                class="fas fa-list-alt text-primary pr-3"></i>Đơn hàng</a></li>
                    <li class="list-info-item"><a class="list-info-item-link" href="/voucher"><i
                                class="fas fa-gift text-primary pr-3"></i>Kho voucher</a></li>
                    <li class="list-info-item"><a class="list-info-item-link" href="/notification"><i
                                class="fas fa-bullhorn text-primary pr-3"></i>Thông báo</a></li>
                    <li class="list-info-item"><a class="list-info-item-link" href="/sign-out"><i
                                class="fas fa-sign-out-alt  text-primary pr-3"></i>Đăng xuất</a></li>
                </ul>
            </div>
            @yield('content-child')
        </div>
    </div>
@endsection
