@extends('profile.profile')
@section('title')
    Thông tin tài khoản
@endsection
@section('content-child')
    <form action="/profile/update-password" method="post" class="col-lg-9" id="updatePassword">
        <div class="row">
            <div class="col-12 p-0">
                <nav class="breadcrumb bg-light mb-3">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Đổi mật khẩu</span>
                </nav>
            </div>
        </div>
        <div class="row bg-light">
            <div class="col-lg-10">
                <div class="p-2 pt-3">
                    <div class="col-md-12 form-group">
                        <label class="pb-2">Tên đăng nhập</label>
                        <input class="form-control" type="text" disabled value="{{ $user->username }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="pb-2">Mật khẩu cũ</label>
                        <input class="form-control" type="password" id="oldPassword" name="oldPassword">
                        <small class="error-message"></small>
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="pb-2">Mật khẩu mới</label>
                        <input class="form-control" type="password" id="password" name="password">
                        <small class="error-message"></small>
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="pb-2">Nhập lại mật khẩu</label>
                        <input class="form-control" type="password" id="re_password" name="re_password">
                        <small class="error-message"></small>
                    </div>
                    <div class="mt-4" style="text-align:center">
                        <button type="submit" class="btn btn-primary mb-3" id="btnUpdate">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
        @csrf
    </form>

    <script>
        const input = document.getElementById('file');
        const image = document.getElementById('img-preview');

        input.addEventListener('change', (e) => {
            if (e.target.files.length) {
                const src = URL.createObjectURL(e.target.files[0]);
                image.src = src;
            }
        });
    </script>
@endsection
