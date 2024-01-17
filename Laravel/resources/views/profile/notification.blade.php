@extends('profile.profile')
@section('title')
    Thông báo
@endsection
@section('content-child')
    <div class="col-md-9">
        <div class="row">
            <div class="col-12 p-0">
                <nav class="breadcrumb bg-light mb-3">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Thông báo</span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 pb-4 mb-3" style="background: white">
                <div class="p-2 pt-3">
                    <div class="row pl-4 pr-4">
                        @foreach ($notifications as $n)
                            <div class="col-md-12 form-voucher-content pl-0 wow fadeInUp" data-wow-delay="0.1s"
                                style="height: 90px">
                                <div class="form-notification-img">
                                    @if ($n->type == 1)
                                        <img class="w-100 h-100"
                                            src="http://localhost:8000/assets/img/icon/notification.webp">
                                    @else
                                        <img class="w-100 h-100" src="http://localhost:8000/assets/img/icon/order.webp">
                                    @endif
                                </div>
                                <div class="form-voucher-info pr-0">
                                    <a href="{{ $n->full_path }}" class="notification-title">{{ $n->name }}</a>
                                    <span class="notification-value">{!! $n->content !!}</span>
                                    <span
                                        class="notification-value mt-2">{{ date_format($n->created_at, 'Y-m-d H:i:s') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <nav class="w-100">
                <ul class="pagination justify-content-center">
                    @if ($notifications->previousPageUrl())
                        <li class="page-item">
                            <a class="page-link" style="cursor:pointer"
                                onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')">Trước</a>
                        </li>
                    @else
                        <li class="page-item disabled"><a class="page-link">Trước</a></li>
                    @endif
                    @for ($i = 1; $i <= $notifications->lastPage(); $i++)
                        @if ($notifications->currentPage() == $i)
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
                    @if ($notifications->nextPageUrl())
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
