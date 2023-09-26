@extends('components.layout')
@section('title')
    Đăng ký
@endsection
@section('content')
    <div id="wrapper">
        <form action="/sign-up" id="signup" method="POST">
            <div class="w-100" style="text-align: center">
                <h1 class="mb-3" style="color:white">Đăng ký</h1>
            </div>
            <div class="account-group">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" class="account-input" placeholder="Tên đăng nhập" value="{{ old('username') }}">
                <small class="error-message"></small>
            </div>
            <div class="account-group">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" class="account-input" placeholder="Email" value="{{ old('email') }}">
                <small class="error-message"></small>
            </div>
            <div class="account-group">
                <i class="fas fa-key"></i>
                <input type="password" id="password" name="password" class="account-input" placeholder="Mật khẩu" value="{{ old('password') }}">
                <div id="eye">
                    <i class="far fa-eye"></i>
                </div>
                <small class="error-message"></small>
            </div>
            <div class="account-group">
                <i class="fas fa-key"></i>
                <input type="password"  id="re_password" name="re_password" class="account-input" placeholder="Nhập lại mật khẩu" value="{{ old('re_password') }}">
                <div id="eye-re">
                    <i class="far fa-eye"></i>
                </div>
                <small class="error-message"></small>
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary account-btn mt-3">Đăng ký</button>
            </div>
            @csrf
            <div class="mt-3" style="text-align: center">
                <span style="color: #d3d3d3">Bạn đã có tài khoản ? <a href="/sign-in">Đăng nhập</a></span>
            </div>
        </form>
    </div>
@endsection
