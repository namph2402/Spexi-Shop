@php
    $user = Illuminate\Support\Facades\Auth::user();
    $countCart = 0;
    if ($user != null) {
        $carts = App\Models\Cart::whereUserId($user->id)
            ->withCount('items')
            ->first();
        $countCart = $carts->items_count;
    }
@endphp
<header class="bg-info">
    <div class="container-fluid">
        <div class="d-inline-flex align-items-center d-block d-lg-none w-100" style="justify-content: center">
            <a href="/notification" class="btn px-0 mr-1">
                <i class="fas fa-bell text-dark"></i>
            </a>
            <a href="/cart" class="btn px-0 ml-3">
                <i class="fas fa-shopping-cart text-dark"></i>
                <span class="badge text-dark border border-dark rounded-circle"
                    style="padding-bottom: 2px;">{{ $countCart }}</span>
            </a>
            <a href="/profile" class="btn px-0 ml-3">
                <i class="fas fa-user text-dark"></i>
            </a>
        </div>
        <div class="row align-items-center py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="/" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2"
                        style="border-radius: 20px 0px 0px 0px; font-family: 'Pacifico', cursive; font-size: 2.1rem;">{{ $data['Name'] }}</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1"
                        style="border-radius: 0px 0px 20px 0px; font-family: 'Pacifico', cursive; font-size: 2.1rem;">Shop</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="/product_searchs" id="formSearch">
                    <div class="input-group">
                        <input type="search" value="{{ request()->search }}" name="search"
                            class="form-control border-none" style="border-radius: 0px" placeholder="Tìm kiếm sản phẩm">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text bg-transparent text-primary border-none">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0 text-white">Dịch vụ khách hàng</p>
                <a class="m-0" href="tel:{{ $data['Phone'] }}">{{ $data['PhoneView'] }}</a>
            </div>
        </div>
    </div>
    <div class="container-fluid mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse"
                    href="#navbar-vertical" style="height: 50px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Danh mục</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
                    id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                        @foreach ($categories as $c)
                            @if (count($c->childrens) > 0)
                                <div class="cate-item nav-item dropdown dropright list-category">
                                    <a href="{{ $c->full_path }}" class="nav-link dropdown-toggle">{{ $c->name }}<i
                                            class="fa fa-angle-right float-right mt-1"></i></a>
                                    <div
                                        class="dropdown-menu position-absolute rounded-0 border-0 m-0 list-category-childrens">
                                        @foreach ($c->childrens as $child)
                                            <a href="{{ $child->full_path }}"
                                                class="dropdown-item">{{ $child->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $c->full_path }}"
                                    class="cate-item nav-item nav-link">{{ $c->name }}</a>
                            @endif
                        @endforeach
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg navbar-dark py-3 py-lg-0 px-0">
                    <a href="/" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2"
                            style="border-radius: 20px 0px 0px 0px; font-family: 'Pacifico', cursive; font-size: 2rem;">{{ $data['Name'] }}</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1"
                            style="border-radius: 0px 0px 20px 0px; font-family: 'Pacifico', cursive; font-size: 2rem;">Shop</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            @foreach ($menus as $m)
                                @if (count($m->childrens) == 0)
                                    @if (Request::url() == $m->full_path || (isset($url) && $url == $m->full_path))
                                        <a href="{{ $m->full_path }}"
                                            class="nav-item nav-link active">{{ $m->name }}</a>
                                    @else
                                        <a href="{{ $m->full_path }}"
                                            class="nav-item nav-link">{{ $m->name }}</a>
                                    @endif
                                @else
                                    @if (Request::url() == $m->full_path)
                                        <div class="nav-item dropdown list-menu">
                                            <a href="{{ $m->full_path }}"
                                                class="nav-link dropdown-toggle active">{{ $m->name }}
                                                <i class="fa fa-angle-down mt-1 ml-1"></i></a>
                                            <div
                                                class="dropdown-menu bg-primary rounded-0 border-0 m-0 list-menu-childrens">
                                                @foreach ($m->childrens as $c)
                                                    <a href="{{ $c->url }}"
                                                        class="dropdown-item">{{ $c->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="nav-item dropdown list-menu">
                                            <a href="{{ $m->full_path }}"
                                                class="nav-link dropdown-toggle">{{ $m->name }}
                                                <i class="fa fa-angle-down mt-1 ml-1"></i></a>
                                            <div
                                                class="dropdown-menu bg-primary rounded-0 border-0 m-0 list-menu-childrens">
                                                @foreach ($m->childrens as $c)
                                                    <a href="{{ $c->url }}"
                                                        class="dropdown-item">{{ $c->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            <a href="/notification" class="btn px-0 mr-1">
                                <i class="fas fa-bell text-primary"></i>
                            </a>
                            <a href="/cart" class="btn px-0 ml-3">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle"
                                    style="padding-bottom: 2px;">{{ $countCart }}</span>
                            </a>
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-user text-primary"></i><i class="fa fa-chevron-down"
                                    style="position: relative;top: 2px; left: 5px; color: #F5F5F5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-0"
                                style="right: -1px; top: 49px; border-top: none;">
                                @if ($user != null)
                                    <a class="dropdown-item link-user" href="/profile">Tài khoản</a>
                                    <a class="dropdown-item link-user" href="/sign-out">Đăng xuất</a>
                                @else
                                    <a class="dropdown-item link-user" href="/sign-in">Đăng nhập</a>
                                    <a class="dropdown-item link-user" href="/sign-up">Đăng ký</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
