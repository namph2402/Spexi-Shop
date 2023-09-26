@extends('components.layout')
@section('title')
    Giới thiệu
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Giới thiệu</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
            <span class="bg-secondary pr-3">Giới thiệu</span>
        </h2>
        <div class="row px-xl-5">
            <div class="col-lg-8 mb-5">
                <div class="contact-form bg-light p-30">
                    {!! $post->content ?? '' !!}
                </div>
            </div>
            <div class="col-lg-4 mb-5">
                <div class="bg-light p-20 mb-30">
                    <iframe style="width: 100%; height: 400px;"
                        src="{{ $data['Map'] }}"
                        frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
                <div class="bg-light p-30 mb-3">
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{ $data['Address'] }}</p>
                    <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ $data['Email'] }}</p>
                    <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ $data['PhoneView'] }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
