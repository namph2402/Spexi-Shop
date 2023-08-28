@extends('components.layout')
@section('title')
    Tìm kiếm
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item">Tìm kiếm</span>
                    <span class="breadcrumb-item active">{{ Request::get('search') }}</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row px-xl-5">
            <form action="{{ Request::url() }}" class="col-lg-3 col-md-4 mb-3" id="formSearchP">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Lọc theo giá</span></h5>
                <div class="bg-light mb-30" style="padding: 1rem;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <label class="label-price">Từ</label>
                        <input type="number" name="priceFrom" value="{{ request()->priceFrom }}" class="input-price priceItem">
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <label class="label-price">Đến</label>
                        <input type="number" name="priceTo" value="{{ request()->priceTo }}" class="input-price priceItem">
                    </div>
                </div>
                <div class="search-variant">
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
                <button class="btn btn-primary w-100">Lọc</button>
            </form>
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-2 d-flex flex-wrap p-0 product-list">
                        @foreach ($products as $p)
                            <div class="col-lg-4 col-md-6 col-sm-6 pb-1 product">
                                <div class="product-item bg-light mb-4">
                                    <div class="product-img position-relative overflow-hidden">
                                        <img class="img-fluid w-100" src="{{ $p->image }}" alt="{{ $p->name }}">
                                    </div>
                                    <div class="product-text text-center py-4">
                                        <a class="product-text-name h6 text-decoration-none text-truncate"
                                            href="{{ $p->full_path }}">{{ $p->name }}</a>
                                        <div class="d-flex align-items-center justify-content-center mt-2">
                                            @if ($p->sale_price == $p->price)
                                                <h5 class="product-text-price">{{ number_format($p->price, 0, '.', '.') }} đ</h5>
                                            @else
                                                <h5 class="product-text-price">{{ number_format($p->sale_price, 0, '.', '.') }} đ</h5>
                                                <h6 class="product-text-price text-muted ml-2">
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
                    <div class="col-12">
                        <nav>
                            <ul class="pagination justify-content-center">
                                @if ($products->previousPageUrl())
                                    <li class="page-item">
                                        <a class="page-link" style="cursor:pointer"
                                            onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')">Trước</a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><a class="page-link">Trước</a></li>
                                @endif
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    @if ($products->currentPage() == $i)
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
                                @if ($products->nextPageUrl())
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
            </div>
        </div>
    </div>
@endsection
