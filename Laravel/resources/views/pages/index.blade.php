@extends('components.layout')
@section('title')
    Trang chủ
@endsection
@section('content')
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-12">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @foreach ($bannerMains as $key => $b)
                            @if ($key == 0)
                                <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                            @else
                                <li data-target="#header-carousel" data-slide-to="{{ $key }}"></li>
                            @endif
                        @endforeach
                    </ol>
                    @if (count($bannerMains) > 0)
                        <div class="carousel-inner">
                            @foreach ($bannerMains as $key => $b)
                                @if ($key == 0)
                                    <div class="carousel-item position-relative active" style="height: 430px;">
                                        <img class="position-absolute w-100 h-100" src="{{ $b->image }}"
                                            style="object-fit: cover;">
                                        <div
                                            class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                            <div class="p-3" style="max-width: 700px;">
                                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">
                                                    {{ $b->name }}</h1>
                                                <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                                    {{ $b->summary }}</p>
                                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                                    href="{{ $b->href }}">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="carousel-item position-relative" style="height: 430px;">
                                        <img class="position-absolute w-100 h-100" src="{{ $b->image }}"
                                            style="object-fit: cover;">
                                        <div
                                            class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                            <div class="p-3" style="max-width: 700px;">
                                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">
                                                    {{ $b->name }}</h1>
                                                <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                                    {{ $b->summary }}</p>
                                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                                    href="{{ $b->href }}">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Sản phẩm chất lượng</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Miễn phí vận chuyển</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Giao hàng trong tuần</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Hỗ trợ 24/7</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4 test"><span class="bg-secondary pr-3">Các sản
                phẩm</span></h2>
        <div class="row px-xl-5 pb-3">
            @foreach ($categories as $c)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <a class="text-decoration-none" href="{{ $c->full_path }}">
                        <div class="cat-item d-flex align-items-center mb-4">
                            <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                <img class="img-fluid" src="{{ $c->image }}" alt="">
                            </div>
                            <div class="flex-fill pl-3">
                                <h6>{{ $c->name }}</h6>
                                @php
                                    $total = 0;
                                    if (count($c->childrens) > 0) {
                                        foreach ($c->childrens as $child) {
                                            foreach ($child->products as $p) {
                                                foreach ($p->warehouses as $w) {
                                                    $total += $w->quantity;
                                                }
                                            }
                                        }
                                    } else {
                                        foreach ($c->products as $p) {
                                            foreach ($p->warehouses as $w) {
                                                $total += $w->quantity;
                                            }
                                        }
                                    }
                                @endphp
                                <small class="text-body">{{ $total }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @if ($featured)
        <div class="container-fluid pt-4 pb-3">
            <a href="{{ $featured->full_path }}"
                class="section-title position-relative text-uppercase mx-xl-5 mb-4 link-title test"><span
                    class="bg-secondary pr-3">Sản phẩm
                    nổi bật</span></a>
            <div class="row px-xl-5">
                @foreach ($featured->productViews as $key => $p)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ $p->image }}" alt="{{ $p->name }}">
                            </div>
                            <div class="text-center py-4">
                                <a class="h6 text-decoration-none text-truncate"
                                    href="{{ $p->full_path }}">{{ $p->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    @if ($p->sale_price == $p->price)
                                        <h5>{{ number_format($p->price, 0, '.', '.') }} đ</h5>
                                    @else
                                        <h5>{{ number_format($p->sale_price, 0, '.', '.') }} đ</h5>
                                        <h6 class="text-muted ml-2">
                                            <del>{{ number_format($p->price, 0, '.', '.') }}
                                                đ
                                            </del>
                                        </h6>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    @php
                                        $medium = 0;
                                        if (count($p->comments) > 0) {
                                            $total = 0;
                                            foreach ($p->comments as $c) {
                                                $total += $c->rating;
                                            }
                                            $medium = round($total / count($p->comments), 1);
                                        }
                                    @endphp
                                    <div class="text-primary mr-2">
                                        @if ($medium == 1)
                                            <small class="fas fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if (1 < $medium && $medium < 2)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star-half-alt"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if ($medium == 2)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if (2 < $medium && $medium < 3)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star-half-alt"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if ($medium == 3)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="far fa-star"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if (3 < $medium && $medium < 4)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star-half-alt"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if ($medium == 4)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="far fa-star"></small>
                                        @endif
                                        @if (4 < $medium && $medium < 5)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star-half-alt"></small>
                                        @endif
                                        @if ($medium == 5)
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                        @endif
                                    </div>
                                    <small style="font-weight:600">({{ $medium }})</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if (count($promotions) > 0)
        <div class="container-fluid pt-4 pb-3">
            <div class="row px-xl-5">
                @foreach ($promotions as $key => $p)
                    @if ($key < 2)
                        @if (count($promotions) == 1)
                            <div class="col-md-12">
                                <div class="product-offer mb-30" style="height: 300px;">
                                    <img class="img-fluid" src="{{ $p->image }}" alt="">
                                    <div class="offer-text">
                                        <h6 class="text-white text-uppercase">{{ $p->summary }}</h6>
                                        <h3 class="text-white mb-3">{{ $p->name }}</h3>
                                        <a href="/promotions/{{ $p->slug }}" class="btn btn-primary">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="product-offer mb-30" style="height: 300px;">
                                    <img class="img-fluid" src="{{ $p->image }}" alt="">
                                    <div class="offer-text">
                                        <h6 class="text-white text-uppercase">{{ $p->summary }}</h6>
                                        <h3 class="text-white mb-3">{{ $p->name }}</h3>
                                        <a href="/promotions/{{ $p->slug }}" class="btn btn-primary">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                    @break
                @endif
            @endforeach
        </div>
    </div>
@endif
@if ($recent)
    <div class="container-fluid pt-4 pb-3">
        <a href="{{ $recent->full_path }}"
            class="section-title position-relative text-uppercase mx-xl-5 mb-4 link-title test"><span
                class="bg-secondary pr-3">Sản phẩm mới</span></a>
        <div class="row px-xl-5">
            @foreach ($recent->productViews as $key => $p)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{{ $p->image }}" alt="{{ $p->name }}">
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate"
                                href="{{ $p->full_path }}">{{ $p->name }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                @if ($p->sale_price == $p->price)
                                    <h5>{{ number_format($p->price, 0, '.', '.') }} đ</h5>
                                @else
                                    <h5>{{ number_format($p->sale_price, 0, '.', '.') }} đ</h5>
                                    <h6 class="text-muted ml-2">
                                        <del>{{ number_format($p->price, 0, '.', '.') }}
                                            đ
                                        </del>
                                    </h6>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                @php
                                    $medium = 0;
                                    if (count($p->comments) > 0) {
                                        $total = 0;
                                        foreach ($p->comments as $c) {
                                            $total += $c->rating;
                                        }
                                        $medium = round($total / count($p->comments), 1);
                                    }
                                @endphp
                                <div class="text-primary mr-2">
                                    @if ($medium == 1)
                                        <small class="fas fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if (1 < $medium && $medium < 2)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star-half-alt"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if ($medium == 2)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if (2 < $medium && $medium < 3)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star-half-alt"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if ($medium == 3)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="far fa-star"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if (3 < $medium && $medium < 4)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star-half-alt"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if ($medium == 4)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="far fa-star"></small>
                                    @endif
                                    @if (4 < $medium && $medium < 5)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star-half-alt"></small>
                                    @endif
                                    @if ($medium == 5)
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                    @endif
                                </div>
                                <small style="font-weight:600">({{ $medium }})</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<div class="container-fluid py-4">
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel vendor-carousel">
                @foreach ($bannerSubs as $b)
                    <a href="{{ $b->href }}">
                        <div class="bg-light p-4">
                            <img src="{{ $b->image }}">
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
