@extends('components.layout')
@section('title')
    {{ $categoryPostMain->name }}
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
                    <span class="breadcrumb-item active">{{ $categoryPostMain->name }}</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-4">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Tìm kiếm</span>
                </h5>
                <div class="bg-light mb-30" style="padding: 1rem">
                    <form action="/post_searchs" id="formSearchPost">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text bg-transparent text-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Danh mục</span>
                </h5>
                <div class="bg-light mb-30" style="padding: 1rem">
                    <form>
                        @foreach ($categoryPosts as $c)
                            <div
                                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                <a href="{{ $c->full_path }}" class="custom-control-label-search"
                                    for="price-all">{{ $c->name }}</a>
                            </div>
                        @endforeach
                    </form>
                </div>
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Gán thẻ</span>
                </h5>
                <div class="bg-light mb-30" style="padding: 1rem">
                    <form>
                        @foreach ($tagPosts as $t)
                            <div
                                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                <a href="{{ $t->full_path }}" class="custom-control-label-search"
                                    for="price-all">{{ $t->name }}</a>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    @foreach ($posts as $p)
                        <div class="col-lg-12 col-md-6 col-sm-6 pb-1 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="post-item bg-light mb-4">
                                <div class="post-img">
                                    <img class="img-fluid w-100 h-100" data-src="{{ $p->image }}"
                                        src="{{ $p->image }}" alt="{{ $p->name }}" style="object-fit: cover;">
                                </div>
                                <div class="post-title">
                                    <div class="post-content">
                                        <a class="text-truncate" href="{{ $p->full_path }}">{{ $p->name }}</a>
                                        <span class="text-muted post-summary">
                                            {!! $p->summary !!}
                                        </span>
                                    </div>
                                    <div class="post-author ml-2">
                                        <i class="fas fa-user" style="margin-right: 5px"></i>{{ $p->article->author_name }}
                                        <i class="fa fa-calendar"
                                            style="margin: 0 5px 0 30px"></i>{{ date_format($p->article->created_at, 'Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-12">
                        <nav>
                            <ul class="pagination justify-content-center">
                                @if ($posts->previousPageUrl())
                                    <li class="page-item">
                                        <a class="page-link" style="cursor:pointer"
                                            onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')"><i
                                                class="fas fa-chevron-circle-left"></i></a>
                                    </li>
                                @else
                                    <li class="page-item disabled"><a class="page-link"><i
                                                class="fas fa-chevron-circle-left"></i></a></li>
                                @endif
                                @for ($i = 1; $i <= $posts->lastPage(); $i++)
                                    @if ($posts->currentPage() == $i)
                                        <li class="page-item active">
                                            <a class="page-link">{{ $i }}</a>
                                        </li>
                                    @else
                                        @if ($posts->currentPage() + 3 >= $i && $posts->currentPage() - 3 <= $i)
                                            <li class="page-item">
                                                <a class="page-link" style="cursor:pointer"
                                                    onclick="setParamsPage('page','{{ $i }}')">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endfor
                                @if ($posts->nextPageUrl())
                                    <li class="page-item">
                                        @if ((int) Request::get('page') == 0)
                                            <a class="page-link" style="cursor:pointer"
                                                onclick="setParamsPage('page','{{ (int) Request::get('page') + 2 }}')"><i
                                                    class="fas fa-chevron-circle-right"></i></a>
                                        @else
                                            <a class="page-link" style="cursor:pointer"
                                                onclick="setParamsPage('page','{{ (int) Request::get('page') + 1 }}')"><i
                                                    class="fas fa-chevron-circle-right"></i></a>
                                        @endif
                                    </li>
                                @else
                                    <li class="page-item disabled"><a class="page-link"><i
                                                class="fas fa-chevron-circle-right"></i></a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
