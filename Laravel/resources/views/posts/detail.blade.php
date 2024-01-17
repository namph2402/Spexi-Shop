@extends('components.layout')
@section('title')
    {{ $post->name }}
@endsection
@section('url')
    {{ $url = config('app.url') . '/posts' }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <a class="breadcrumb-item text-dark" href="/posts">Tin tức</a>
                    <span class="breadcrumb-item active">Chi tiết bài viết</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-9 mb-5">
                <div class="bg-light p-30">
                    <div class="pb-3">
                        <h1>{{ $post->name }}</h1>
                        <div class="author-info">
                            <span>Đăng bởi: {{ $post->article->author_name }}</span>
                            <span>Ngày đăng: {{ date_format($post->article->created_at, 'Y-m-d') }}</span>
                        </div>
                    </div>
                    <div class="content-blog wow fadeIn" data-wow-delay="0.1s">
                        {!! $post->article->content !!}
                        <span class="text-end"></span>
                    </div>
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Bình luận
                            ({{ count($post->comments) }})</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Viết bình luận</a>
                    </div>
                    <div class="tab-content">
                        <div class="list-comment tab-pane fade show active" id="tab-pane-1">
                            @foreach ($post->comments as $c)
                                <div class="media mb-4">
                                    <img src="{{ $c->author ? $c->author->profile->avatar : asset('assets/img/private/user.png') }}"
                                        alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px; border-radius:50%">
                                    <div class="media-body">
                                        @php
                                            $dateNow = now();
                                            $date = date_format($dateNow, 'Y-m-d');
                                            $dateCreate = date_format($c->created_at, 'Y-m-d');
                                        @endphp
                                        <h6>{{ $c->author ? $c->author->username : 'User' }}<small
                                                style="margin-left: 10px">
                                                @if ($date == $dateCreate)
                                                    <i>{{ date_format($c->created_at, 'H:i:s') }}</i>
                                                @else
                                                    <i>{{ date_format($c->created_at, 'Y-m-d H:i:s') }}</i>
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
                        <div class="tab-pane fade" id="tab-pane-2">
                            <form action="/comment" method="POST" id="formComment">
                                <div class="d-flex my-3" style="align-items: center">
                                    <p class="mb-0 mr-2">Đánh giá :</p>
                                    <div class="text-primary rate">
                                        <input type="radio" id="star5" name="rating" value="5" />
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="1" />
                                        <label for="star1" title="text">1 star</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message">Nội dung</label>
                                    <textarea id="message" cols="30" rows="5" class="form-control" name="content"></textarea>
                                </div>
                                <input type="text" hidden name="article_id" value="{{ $post->article->id }}">
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary px-3">Đánh giá</button>
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 mb-5">
                <div class="bg-light p-30 mb-30">
                    @if (count($post->relateds) > 0)
                        <h4 class="font-weight-semi-bold mb-4">Bài viết liên quan</h4>
                        <div class="blog-list">
                            @foreach ($post->relateds as $r)
                                <div class="blog-item">
                                    <img class="blog-item-img w-100" data-src="{{ $r->post->image }}"
                                        src="{{ $r->post->image }}" alt="{{ $r->post->name }}" style="object-fit: cover;">
                                    <div class="blog-item-info">
                                        <a href="{{ $r->post->full_path }}"
                                            class="blog-item-title">{{ $r->post->name }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @if (count($categoryPost) > 0)
                            <h4 class="font-weight-semi-bold mb-4">Bài viết liên quan</h4>
                            <div class="blog-list">
                                @foreach ($categoryPost as $c)
                                    <div class="blog-item">
                                        <img class="blog-item-img w-100" data-src="{{ $c->image }}"
                                            src="{{ $c->image }}" alt="{{ $c->name }}"
                                            style="object-fit: cover;">
                                        <div class="blog-item-info">
                                            <a href="{{ $c->full_path }}"
                                                class="blog-item-title">{{ $c->name }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
