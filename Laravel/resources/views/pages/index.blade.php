@extends('components.layout')
@section('title')
    Trang chủ
@endsection
@section('content')
    <div class="container-fluid container-item mb-3">
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
                                    <div class="carousel-item position-relative banner-item active">
                                        <img class="position-absolute w-100 h-100" data-src="{{ $b->image }}" src="{{ $b->image }}"
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
                                    <div class="carousel-item position-relative banner-item">
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
    <div class="container-fluid container-item pt-4">
        <div class="row px-xl-5 pb-3 info-list wow fadeIn" data-wow-delay="0.1s">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1 info-item">
                <div class="d-flex align-items-center bg-light mb-4 p-30">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0 info-item-text">Sản phẩm chất lượng</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1 info-item">
                <div class="d-flex align-items-center bg-light mb-4 p-30">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0 info-item-text">Mua hàng trên toàn quốc</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1 info-item">
                <div class="d-flex align-items-center bg-light mb-4 p-30">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0 info-item-text">Giao hàng trong tuần</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1 info-item">
                <div class="d-flex align-items-center bg-light mb-4 p-30">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0 info-item-text">Hỗ trợ khách hàng 24/7</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid container-item pt-4">
        <h3 class="section-title position-relative text-uppercase mx-xl-5 mb-4 test"><span class="bg-secondary pr-3">Các sản phẩm</span></h3>
        <div class="row px-xl-5 pb-3 list-cate">
            @foreach ($categories as $c)
                <div class="col-lg-4 col-md-4 col-sm-6 pb-1 category wow fadeInUp" data-wow-delay="0.1s">
                    <a class="d-block text-decoration-none cate-list" href="{{ $c->full_path }}">
                        <div class="cat-item d-flex align-items-center mb-4 cate-item">
                            <div class="overflow-hidden cate-img">
                                <img class="img-fluid" data-src="{{ $c->image }}" src="{{ $c->image }}" alt="{{ $c->name }}">
                            </div>
                            <div class="flex-fill pl-3">
                                <h6 style="text-transform: capitalize">{{ $c->name }}</h6>
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
    @if (count($featured))
        <div class="container-fluid container-item pt-4 pb-3">
            <a href="{{ $tagHot->full_path }}" class="section-title position-relative text-uppercase mx-xl-5 mb-4 link-title test" style="font-size:1.75rem">
                <span class="bg-secondary pr-3">Sản phẩm hot</span>
            </a>
            <div class="row px-xl-5">
                <div class="col-12 pb-2 d-flex flex-wrap p-0 product-list">
                @foreach ($featured as $p)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1 product wow fadeInUp" data-wow-delay="0.1s">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" data-src="{{ $p->image }}" src="{{ $p->image }}" alt="{{ $p->name }}">
                            </div>
                            <div class="product-text text-center py-3">
                                <a class="h6 text-decoration-none text-truncate"
                                    href="{{ $p->full_path }}">{{ $p->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    @if ($p->sale_price == $p->price)
                                        <h5 class="product-text-price price-sale">{{ number_format($p->price, 0, '.', '.') }}đ</h5>
                                    @else
                                        <h5 class="product-text-price price-sale">{{ number_format($p->sale_price, 0, '.', '.') }}đ</h5>
                                        <h6 class="product-text-price text-muted ml-2">
                                            <del>{{ number_format($p->price, 0, '.', '.') }}đ</del>
                                        </h6>
                                    @endif
                                </div>
                                @php
                                    $medium = 0;
                                    $value = 0;
                                    if (count($p->comments) > 0) {
                                        $total = 0;
                                        foreach ($p->comments as $c) {
                                            $total += $c->rating;
                                        }
                                        $medium = round($total / count($p->comments), 1);
                                    }
                                    if(count($p->warehouseViews) > 0) {
                                        foreach ($p->warehouseViews as $w) {
                                            $value += $w->use_quantity;
                                        }
                                        if($value < 1000) {
                                            $quantity = $value;
                                        } else {
                                            $quantity = round($value / 1000, 1)."K";
                                        }
                                    }
                                @endphp
                                @if ($medium > 0 || $value > 0)
                                    <div class="rating-icon">
                                        @if ($medium > 0)
                                            <span class="rating-icon-value">
                                                <small class="text-primary fas fa-star"></small>
                                                <p class="rating-icon-number">{{$medium}}</p>
                                            </span>
                                        @endif
                                        @if ($medium > 0 && $value > 0)
                                            <span style="color: white; margin:0 3px">|</span>
                                        @endif
                                        @if ($value > 0)
                                            <span class="rating-icon-value">
                                                <p class="rating-icon-number">Đã bán: {{ $quantity }}</p>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                @if ($p->sale_price < $p->price)
                                    @php
                                        $precent = round(100 - ($p->sale_price / $p->price * 100));
                                    @endphp
                                    <div class="sale-icon">
                                        <span class="sale-icon-value">-{{$precent}}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($key == 7)
                    @break
                    @endif
                @endforeach
                </div>
            </div>
        </div>
    @endif
    @if (count($promotions) > 0)
        <div class="container-fluid container-item pt-4 pb-3">
            <div class="row px-xl-5 promotion-list wow fadeIn" data-wow-delay="0.1s">
                @foreach ($promotions as $key => $p)
                    @if ($key < 2)
                        @if (count($promotions) == 1)
                            <div class="col-md-12 promotion-index">
                                <div class="product-offer mb-30">
                                    <img class="img-fluid" data-src="{{ $p->image }}" src="{{ $p->image }}" alt=""{{ $p->name }}>
                                    <div class="offer-text">
                                        <h6 class="text-white text-uppercase">{{ $p->summary }}</h6>
                                        <h3 class="text-white mb-3">{{ $p->name }}</h3>
                                        <a href="/promotions/{{ $p->slug }}" class="btn btn-primary">Xem thêm</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6 promotion-index promotion-{{$key}}">
                                <div class="product-offer mb-30">
                                    <img class="img-fluid" data-src="{{ $p->image }}" src="{{ $p->image }}" alt="{{ $p->name }}">
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
    @if (count($recent) > 0)
        <div class="container-fluid container-item pt-4 pb-3">
            <a href="{{ $tagNew->full_path }}" class="section-title position-relative text-uppercase mx-xl-5 mb-4 link-title test" style="font-size:1.75rem">
                <span class="bg-secondary pr-3">Sản phẩm mới</span>
            </a>
            <div class="row px-xl-5">
                <div class="col-12 pb-2 d-flex flex-wrap p-0 product-list">
                @foreach ($recent as $p)
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1 product wow fadeInUp" data-wow-delay="0.1s">
                        <div class="product-item bg-light mb-4">
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" data-src="{{ $p->image }}" src="{{ $p->image }}" alt="{{ $p->name }}">
                            </div>
                            <div class="product-text text-center py-3">
                                <a class="h6 text-decoration-none text-truncate"
                                    href="{{ $p->full_path }}">{{ $p->name }}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">
                                    @if ($p->sale_price == $p->price)
                                        <h5 class="product-text-price price-sale">{{ number_format($p->price, 0, '.', '.') }}đ</h5>
                                    @else
                                        <h5 class="product-text-price price-sale">{{ number_format($p->sale_price, 0, '.', '.') }}đ</h5>
                                        <h6 class="product-text-price text-muted ml-2">
                                            <del>{{ number_format($p->price, 0, '.', '.') }}đ</del>
                                        </h6>
                                    @endif
                                </div>
                                @php
                                    $medium = 0;
                                    $value = 0;
                                    if (count($p->comments) > 0) {
                                        $total = 0;
                                        foreach ($p->comments as $c) {
                                            $total += $c->rating;
                                        }
                                        $medium = round($total / count($p->comments), 1);
                                    }
                                    if(count($p->warehouseViews) > 0) {
                                        foreach ($p->warehouseViews as $w) {
                                            $value += $w->use_quantity;
                                        }
                                        if($value < 1000) {
                                            $quantity = $value;
                                        } else {
                                            $quantity = round($value / 1000, 1)."K";
                                        }
                                    }
                                @endphp
                                @if ($medium > 0 || $value > 0)
                                    <div class="rating-icon">
                                        @if ($medium > 0)
                                        <span class="rating-icon-value">
                                            <small class="text-primary fas fa-star"></small>
                                            <p class="rating-icon-number">{{$medium}}</p>
                                        </span>
                                        @endif
                                        @if ($medium > 0 && $value > 0)
                                        <span style="color: white; margin:0 3px">|</span>
                                        @endif
                                        @if ($value > 0)
                                        <span class="rating-icon-value">
                                            <p class="rating-icon-number">Đã bán: {{ $quantity }}</p>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                                @if ($p->sale_price < $p->price)
                                    @php
                                        $precent = round(100 - ($p->sale_price / $p->price * 100));
                                    @endphp
                                    <div class="sale-icon">
                                        <span class="sale-icon-value">-{{$precent}}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    @endif
    @if (count($bannerSubs) > 0)
    <div class="container-fluid container-item py-4">
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel wow fadeIn" data-wow-delay="0.1s">
                    @foreach ($bannerSubs as $b)
                        <a href="{{ $b->href }}">
                            <div class="bg-light p-4">
                                <img  data-src="{{ $b->image }}" src="{{ $b->image }}">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (count($posts))
    <div class="container-fluid container-item pt-4 pb-3">
        <a href="/posts" class="section-title position-relative text-uppercase mx-xl-5 mb-4 link-title test" style="font-size:1.75rem">
            <span class="bg-secondary pr-3">Bài viết mới</span>
        </a>
        <div class="row px-xl-5">
            <div class="col-12 pb-2 d-flex flex-wrap p-0 product-list">
            @foreach ($posts as $p)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1 product wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-item bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100 post-img-index" data-src="{{ $p->image }}" src="{{ $p->image }}" alt="{{ $p->name }}" style="height: 250px; object-fit: cover;">
                        </div>
                        <div class="product-text text-center py-3">
                            <a class="h6 text-decoration-none text-truncate"
                                href="{{ $p->full_path }}">{{ $p->name }}</a>
                            <div class="d-flex align-items-center justify-content-between mt-2" style="padding: 0 10px; font-size: 15px">
                                <div>
                                    <i class="fas fa-user" style="margin-right: 5px"></i>{{$p->article->author_name}}
                                </div>
                                <div>
                                    <i class="fas fa-comment" style="margin-right: 5px"></i>{{count($p->comments)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($key == 3)
                @break
                @endif
            @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection
