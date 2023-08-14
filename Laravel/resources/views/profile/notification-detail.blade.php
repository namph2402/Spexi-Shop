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
                    <a class="breadcrumb-item text-dark" href="/notification">Thông báo</a>
                    <span class="breadcrumb-item active">Chi tiết</span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 pb-4 mb-3" style="background: white">
                <div class="p-2 pt-3">
                    <div class="row pl-4 pr-4">
                        <div class="col-md-12">
                            <h4 class="mb-4">{{ $notification->name }}</h4>
                            <span>{!! $notification->content !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
