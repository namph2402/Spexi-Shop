<footer>
    <div class="container-fluid bg-dark text-secondary mt-3 pt-3 wow fadeIn" data-wow-delay="0.1s">
        <div class="row px-xl-5 pt-4">
            <div class="col-lg-4 col-md-12 mb-4 pr-3 pr-xl-5">
                <h5 class="text-primary text-uppercase mb-4">{{ $data['Name'] }} shop</h5>
                <p class="mb-4">{{ $data['Introduce'] }}</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{ $data['Address'] }}</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ $data['Email'] }}</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ $data['PhoneView'] }}</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-4 footer-link">
                        <h5 class="text-primary text-uppercase mb-4">Liên kết</h5>
                        <div class="d-flex flex-column justify-content-start">
                            @foreach ($links as $l)
                                <a class="text-secondary mb-2" href="{{ $l->full_path }}"><i
                                        class="fa fa-angle-right mr-2"></i>{{ $l->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 footer-category">
                        <h5 class="text-primary text-uppercase mb-4">Danh mục</h5>
                        <div class="d-flex flex-column justify-content-start">
                            @foreach ($categories as $c)
                                <a class="text-secondary mb-2" href="{{ $c->full_path }}"><i
                                        class="fa fa-angle-right mr-2"></i>{{ $c->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="text-primary text-uppercase mb-4">NHẬN THÔNG TIN</h5>
                        <p>Theo dõi để được cập nhật tin tức</p>
                        <form action="/form-data" id="formEmail" method="POST" class="footer-mail">
                            <div class="input-group">
                                <input type="email" style="border-radius: 0;" class="form-control" name="email" placeholder="Email của bạn">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Đăng ký</button>
                                </div>
                            </div>
                            @csrf
                        </form>
                        <div class="d-flex mt-3" class="footer-icon">
                            <a class="btn btn-primary btn-square mr-2" href="{{ $data['Tiktok'] }}"><img style="width:15px" src="{{asset('assets/img/icon/tiktok.png')}}" alt=""></a>
                            <a class="btn btn-primary btn-square mr-2" href="{{ $data['Facebook'] }}"><i
                                    class="fab fa-facebook-f" style="color: black"></i></a>
                            <a class="btn btn-primary btn-square" href="{{ $data['Instagram'] }}"><i class="fab fa-instagram" style="color: black"></i></a>
                        </div>
                        <div class="mt-4">
                            <a href="/huong-dan-chon-size" class="d-block policy">Hướng dẫn chọn size</a>
                            <a href="/chinh-sach-bao-mat" class="d-block policy" style="margin: 5px 0">Chính sách bảo
                                mật</a>
                            <a href="/chinh-sach-mua-hang" class="d-block policy">Chính sách mua hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
