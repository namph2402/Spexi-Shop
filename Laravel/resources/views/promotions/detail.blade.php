@extends('components.layout')
@section('title')
    {{ $promotionMain->name }}
@endsection
@section('url')
    {{ $url = config('app.url').'/promotions'}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <a class="breadcrumb-item text-dark" href="/promotions">Khuyến mãi</a>
                    <span class="breadcrumb-item active">{{ $promotionMain->name }}</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        @if ($promotionMain->image != null)
            <div class="row px-xl-5 pb-5">
                <img class="col-12 banner-promotion" data-src="{{ $promotionMain->image }}" src="{{ $promotionMain->image }}" alt="{{ $promotionMain->name }}" style="max-height: 400px;">
            </div>
        @endif
        <div class="row px-xl-5 pb-5">
            <div class="bg-light list-promotion text-uppercase">
                <li class="promotion-item">
                    <span class="promotion-link promotion-active">{{ $promotionMain->name }}</span>
                </li>
                <li class="promotion-item">
                    <a href="/promotions" class="promotion-link">Tất cả</a>
                </li>
                @foreach ($promotions as $p)
                    <li class="promotion-item">
                        <a href="{{ $p->full_path }}" class="promotion-link">{{ $p->name }}</a>
                    </li>
                @endforeach
            </div>
        </div>

        <div class="row px-xl-5">
            <div class="col-md-1 btn-filter d-none">
                <button class="btn btn-primary float-right" onclick="changeForm()">Lọc</button>
            </div>
            <form action="{{ Request::url() }}" class="col-lg-3 col-md-4 mb-3 formSearchP wow fadeIn" data-wow-delay="0.1s" id="formSearchP">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Lọc theo giá</span></h5>
                <div class="search-price mb-30">
                    <div class="group">
                        <div class="progress1"></div>
                        <div class="range-input">
                            <input class="range-min" name="priceFrom" max="1000000" step="10000" type="range" value="{{ request()->priceFrom ? request()->priceFrom : 0}}">
                            <input class="range-max" name="priceTo" max="1000000" step="10000" type="range" value="{{ request()->priceTo ? request()->priceTo : 1000000}}">
                        </div>
                        <div class="range-text">
                            <div class="text-min">{{ request()->priceFrom ? request()->priceFrom : 0}}</div>
                            <div class="text-max">{{ request()->priceTo ? number_format(request()->priceTo, 0, '.', '.') : number_format(1000000, 0, '.', '.')}}</div>
                        </div>
                    </div>
                </div>
                <div class="search-variant mb-30">
                    <div class="search-color">
                        <h5 class="section-title position-relative text-uppercase mb-3">
                            <span class="bg-secondary pr-3">Lọc theo màu</span>
                        </h5>
                        <div class="bg-light mb-30 search-group">
                            <div class="row pl-3 pr-3 list-item-search">
                                @foreach ($colors as $c)
                                    <div class="col-md-6 custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                        <input type="checkbox" class="custom-control-input colorItem" value="{{ $c->id }}"
                                            id="{{ $c->name }}" @if (in_array($c->id, $arrColor)) checked @endif>
                                        <label class="custom-control-label" for="{{ $c->name }}">{{ $c->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="text" hidden name="color" id="color" value="All">
                        </div>
                    </div>
                    <div class="search-size">
                        <h5 class="section-title position-relative text-uppercase mb-3">
                            <span class="bg-secondary pr-3">Lọc theo Size</span>
                        </h5>
                        <div class="bg-light mb-30 search-group">
                            <div class="row pl-3 pr-3 list-item-search">
                                @foreach ($sizes as $s)
                                    <div class="col-md-6 custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                        <input type="checkbox" class="custom-control-input sizeItem" value="{{ $s->id }}"
                                            id="{{ $s->name }}" @if (in_array($s->id, $arrSize)) checked @endif>
                                        <label class="custom-control-label" for="{{ $s->name }}">{{ $s->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="text" hidden name="size" id="size" value="All">
                        </div>
                    </div>
                </div>
                <div class="search-variant mb-30">
                    <div class="search-tag">
                        <h5 class="section-title position-relative text-uppercase mb-3">
                            <span class="bg-secondary pr-3">Tag liên quan</span>
                        </h5>
                        <div class="bg-light mb-30 search-group">
                            <div class="row pl-3 pr-3 list-item-search">
                                @foreach ($tags as $t)
                                <li class="promotion-item">
                                    <a class="promotion-link p-2" href="{{ $t->full_path }}">{{ $t->name }}</a>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary w-100">Lọc</button>
            </form>
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-2 d-flex flex-wrap p-0 product-list">
                        @foreach ($products as $p)
                            <div class="col-lg-4 col-md-6 col-sm-6 pb-1 product wow fadeInUp" data-wow-delay="0.1s">
                                <div class="product-item bg-light mb-4">
                                    <div class="product-img position-relative overflow-hidden">
                                        <img class="img-fluid w-100" data-src="{{ $p->image }}" src="{{ $p->image }}" alt="{{ $p->name }}">
                                    </div>
                                    <div class="product-text text-center py-3">
                                        <a class="product-text-name h6 text-decoration-none text-truncate"
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
                                            if (count($p->comments) > 0) {
                                                $total = 0;
                                                foreach ($p->comments as $c) {
                                                    $total += $c->rating;
                                                }
                                                $medium = round($total / count($p->comments), 1);
                                            }
                                        @endphp
                                        @if ($medium > 0)
                                            <div class="rating-icon">
                                                <span class="rating-icon-value">
                                                    <small class="text-primary fas fa-star"></small>
                                                    <p class="rating-icon-number">{{$medium}}</p>
                                                </span>
                                            </div>
                                        @endif
                                        @if (count($p->tags) > 0)
                                            @foreach ($p->tags as $t)
                                                @if ($t->name == 'Sản phẩm hot')
                                                    <div class="hot-icon">
                                                        <img src="{{asset('assets/img/icon/hotico.svg')}}">
                                                    </div>
                                                    @break
                                                @elseif($t->name == 'Sản phẩm mới')
                                                    <div class="new-icon">
                                                        <span class="new-icon-value value-icon">NEW</span>
                                                    </div>
                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-12 wow fadeIn" data-wow-delay="0.1s">
                        <nav>
                            <ul class="pagination justify-content-center">
                                @if ($products->previousPageUrl())
                                    <li class="page-item">
                                        <a class="page-link" style="cursor:pointer"
                                            onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')"><i class="fas fa-chevron-circle-left"></i></a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><a class="page-link"><i class="fas fa-chevron-circle-left"></i></a></li>
                                @endif
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    @if ($products->currentPage() == $i)
                                        <li class="page-item active">
                                            <a class="page-link">{{ $i }}</a>
                                        </li>
                                    @else
                                        @if ($products->currentPage() + 3 >= $i && $products->currentPage() - 3 <= $i)
                                            <li class="page-item">
                                                <a class="page-link" style="cursor:pointer" onclick="setParamsPage('page','{{ $i }}')">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endfor
                                @if ($products->nextPageUrl())
                                    <li class="page-item">
                                        @if ((int) Request::get('page') == 0)
                                            <a class="page-link" style="cursor:pointer"
                                                onclick="setParamsPage('page','{{ (int) Request::get('page') + 2 }}')"><i class="fas fa-chevron-circle-right"></i></a>
                                        @else
                                            <a class="page-link" style="cursor:pointer"
                                                onclick="setParamsPage('page','{{ (int) Request::get('page') + 1 }}')"><i class="fas fa-chevron-circle-right"></i></a>
                                        @endif
                                    </li>
                                @else
                                    <li class="page-item disabled"><a class="page-link"><i class="fas fa-chevron-circle-right"></i></a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
