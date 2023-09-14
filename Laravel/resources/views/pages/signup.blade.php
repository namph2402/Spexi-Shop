@extends('components.layout')
@section('title')
    Đăng ký
@endsection
@section('content')
    <div id="wrapper">
        <form action="/sign-up" id="signup" name="signup" method="POST">
            <div class="w-100" style="text-align: center">
                <h1 class="mb-3" style="color:white">Đăng ký</h1>
            </div>
            <div class="signup-group mb-4">
                <i class="fas fa-user"></i>
                <input type="text" name="username" class="signup-input" placeholder="Nhập tên đăng nhập"
                       value="{{ old('username') }}">
            </div>
            <div class="signup-group mb-4">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="signup-input" placeholder="Nhập email"
                       value="{{ old('email') }}">
            </div>
            <div class="signup-group mb-4">
                <i class="fas fa-key"></i>
                <input type="password" name="password" class="signup-input" placeholder="Mật khẩu"
                       value="{{ old('password') }}">
                <div id="eye">
                    <i class="far fa-eye"></i>
                </div>
            </div>
            <div class="signup-group">
                <i class="fas fa-key"></i>
                <input type="password" name="re_password" class="signup-input" placeholder="Nhập lại mật khẩu"
                       value="{{ old('re_password') }}">
                <div id="eye-re">
                    <i class="far fa-eye"></i>
                </div>
            </div>
            <div style="text-align: center">
                <span class="errSignup" id="errMsg"></span>
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary btn-login mt-3">Đăng ký</button>
            </div>
            @csrf
            <div class="mt-3" style="text-align: center">
                <span style="color: #d3d3d3">Bạn đã có tài khoản? <a href="/sign-in">Đăng nhập</a></span>
            </div>
        </form>
    </div>
@endsection
