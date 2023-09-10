@extends('components.layout')
@section('title')
    {{ $product->name }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <a class="breadcrumb-item text-dark"
                        href="{{ $product->category->full_path }}">{{ $product->category->name }}</a>
                    <span class="breadcrumb-item active">{{ $product->name }}</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner bg-light">
                        @foreach ($product->images as $key => $i)
                            @if ($key == 0)
                                <div class="carousel-item active">
                                    <img class="w-100 h-100" data-src="{{ $i->image }}" src="{{ $i->image }}" alt="{{ $product->name }}">
                                </div>
                            @else
                                <div class="carousel-item">
                                    <img class="w-100 h-100" data-src="{{ $i->image }}" src="{{ $i->image }}" alt="{{ $product->name }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3>{{ $product->name }}</h3>
                    <h4 class="font-weight-semi-bold mb-4" style="color: red">
                        {{ number_format($product->sale_price, 0, '.', '.') }}đ</h4>
                    <div class="my-4 detail_summary">{!! $product->summary !!}</div>
                    <form action="/cart/addItem" method="post" id="formDetail" class="form mb-2 mt3">
                        <div class="d-flex mb-3">
                            <strong class="text-dark mr-3">Size :</strong>
                            @foreach ($sizePs as $key=>$s)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="{{ $s->name }}"
                                        value="{{ $s->id }}" name="size_id" @if($key == 0) checked @endif>
                                    <label class="custom-control-label"
                                        for="{{ $s->name }}">{{ $s->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex mb-4">
                            <strong class="text-dark mr-3">Màu :</strong>
                            @foreach ($colorPs as $key=>$c)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="{{ $c->name }}"
                                        value="{{ $c->id }}" name="color_id" @if($key == 0) checked @endif>
                                    <label class="custom-control-label"
                                        for="{{ $c->name }}">{{ $c->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center mb-2 pt-3 form-detail">
                            <div class="input-group quantity mr-3" style="width: 130px;">
                                <div class="input-group-btn">
                                    <a class="btn btn-primary btn-minus">
                                        <i class="fa fa-minus"></i>
                                    </a>
                                </div>
                                <input type="number" class="form-control bg-secondary border-0 text-center" name="quantity"
                                    value="1">
                                <div class="input-group-btn">
                                    <a class="btn btn-primary btn-plus">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <button class="btn btn-primary px-3" type="submit"><i class="fa fa-shopping-cart mr-1"></i>
                                Thêm vào giỏ
                            </button>
                            <p id="errText" class="err-text d-none">Vui lòng chọn size và màu</p>
                        </div>
                        <input type="text" hidden name="product_id" value="{{ $product->id }}">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Thông tin</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Đánh giá
                            ({{ count($product->comments) }})</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Mô tả</h4>
                            {!! $product->article->content !!}
                        </div>
                        <div class="tab-pane fade" id="tab-pane-2">
                            <div class="row">
                                <div class="col-md-6 list-comment">
                                    @foreach ($product->comments as $c)
                                        <div class="media mb-4">
                                            <img src="{{ $c->author ? $c->author->profile->avatar : asset('assets/img/private/user.png') }}"
                                                alt="image" class="img-fluid mr-3 mt-1"
                                                style="width: 45px; border-radius:50%">
                                            <div class="media-body">
                                                @php
                                                    $dateNow = now();
                                                    $date = date_format($dateNow, 'Y-m-d');
                                                    $dateCreate = date_format($c->created_at, 'Y-m-d');
                                                @endphp
                                                <h6>{{ $c->author ? $c->author->username : 'User' }}<small
                                                        style="margin-left: 10px">
                                                        @if ($date == $dateCreate)
                                                            <i>{!! date_format($c->created_at, 'H:i:s') !!}</i>
                                                        @else
                                                            <i>{!! date_format($c->created_at, 'Y-m-d H:i:s') !!}</i>
                                                        @endif
                                                    </small>
                                                </h6>
                                                <div class="text-primary mb-2">
                                                    @for ($i = 1; $i <= $c->rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for ($i = 1; $i <= 5 - $c->rating; $i++)
                                                        <i class="far fa-star"></i>
                                                    @endfor
                                                </div>
                                                {!! $c->content !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <form action="/comment" method="POST" class="col-md-6" id="formComment">
                                    <h4 class="mb-4">Thêm đánh giá</h4>
                                    <div class="d-flex my-3" style="align-items: center">
                                        <p class="mb-0 mr-2">Đánh giá :</p>
                                        <div class="text-primary rate">
                                            <input type="radio" id="star5" name="rating" value="5"/>
                                            <label for="star5" title="text">5 stars</label>
                                            <input type="radio" id="star4" name="rating" value="4"/>
                                            <label for="star4" title="text">4 stars</label>
                                            <input type="radio" id="star3" name="rating" value="3"/>
                                            <label for="star3" title="text">3 stars</label>
                                            <input type="radio" id="star2" name="rating" value="2"/>
                                            <label for="star2" title="text">2 stars</label>
                                            <input type="radio" id="star1" name="rating" value="1"/>
                                            <label for="star1" title="text">1 star</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Nội dung</label>
                                        <textarea id="message" cols="30" rows="5" class="form-control" name="content"></textarea>
                                    </div>
                                    <input type="text" hidden name="article_id" value="{{ $product->article->id }}">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary px-3">Đánh giá</button>
                                    </div>
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (count($product->relateds) > 0)
        <div class="container-fluid py-5">
            <h3 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
                <span class="bg-secondary pr-3 test">Bạn cũng có thể thích</span>
            </h3>
            <div class="row px-xl-5">
                <div class="col">
                    <div class="owl-carousel related-carousel">
                        @foreach ($product->relateds as $r)
                            @if ($r->product->status == 1)
                                <div class="product-item bg-light">
                                    <div class="product-img position-relative overflow-hidden">
                                        <img class="img-fluid w-100" data-src="{{ $r->product->image }}" src="{{ $r->product->image }}" alt="{{ $r->product->name }}">
                                    </div>
                                    <div class="text-center py-3">
                                        <a class="h6 text-decoration-none text-truncate"
                                            href="{{ $r->product->full_path }}">{{ $r->product->name }}</a>
                                        <div class="d-flex align-items-center justify-content-center mt-2">
                                            @if ($r->product->sale_price == $r->product->price)
                                                <h5 class="product-text-price price-sale">{{ number_format($r->product->price, 0, '.', '.') }}đ</h5>
                                            @else
                                                <h5 class="product-text-price price-sale">{{ number_format($r->product->sale_price, 0, '.', '.') }}đ</h5>
                                                <h6 class="product-text-price text-muted ml-2">
                                                    <del>{{ number_format($r->product->price, 0, '.', '.') }}đ</del>
                                                </h6>
                                            @endif
                                        </div>
                                        @php
                                            $medium = 0;
                                            if (count($r->product->comments) > 0) {
                                                $total = 0;
                                                foreach ($r->product->comments as $c) {
                                                    $total += $c->rating;
                                                }
                                                $medium = round($total / count($r->product->comments), 1);
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
                                        @if ($r->product->sale_price < $r->product->price)
                                            @php
                                                $precent = round(100 - ($r->product->sale_price / $r->product->price * 100));
                                            @endphp
                                            <div class="sale-icon">
                                                <span class="sale-icon-value">-{{$precent}}%</span>
                                            </div>
                                        @elseif(count($r->product->tags) > 0)
                                            @foreach ($r->product->tags as $t)
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
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (count($category) > 0)
        <div class="container-fluid py-5">
            <h3 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
                <span class="bg-secondary pr-3 test">Sản phẩm cùng loại</span>
            </h3>
            <div class="row px-xl-5">
                <div class="col">
                    <div class="owl-carousel related-carousel">
                        @foreach ($category as $c)
                            <div class="product-item bg-light">
                                <div class="product-img position-relative overflow-hidden">
                                    <img class="img-fluid w-100" data-src="{{ $c->image }}" src="{{ $c->image }}" alt="{{ $c->name }}">
                                </div>
                                <div class="text-center py-3">
                                    <a class="h6 text-decoration-none text-truncate"
                                        href="{{ $c->full_path }}">{{ $c->name }}</a>
                                    <div class="d-flex align-items-center justify-content-center mt-2">
                                        @if ($c->sale_price == $c->price)
                                            <h5 class="product-text-price price-sale">{{ number_format($c->price, 0, '.', '.') }}đ</h5>
                                        @else
                                            <h5 class="product-text-price price-sale">{{ number_format($c->sale_price, 0, '.', '.') }}đ</h5>
                                            <h6 class="product-text-price text-muted ml-2">
                                                <del>{{ number_format($c->price, 0, '.', '.') }}đ</del>
                                            </h6>
                                        @endif
                                    </div>
                                    @php
                                        $medium = 0;
                                        if (count($c->comments) > 0) {
                                            $total = 0;
                                            foreach ($c->comments as $cm) {
                                                $total += $cm->rating;
                                            }
                                            $medium = round($total / count($c->comments), 1);
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
                                    @if ($c->sale_price < $c->price)
                                        @php
                                            $precent = round(100 - ($c->sale_price / $c->price * 100));
                                        @endphp
                                        <div class="sale-icon">
                                            <span class="sale-icon-value">-{{$precent}}%</span>
                                        </div>
                                    @elseif(count($c->tags) > 0)
                                        @foreach ($c->tags as $t)
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
