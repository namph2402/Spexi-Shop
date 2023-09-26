@extends('components.layout')
@section('title')
    Đăng nhập
@endsection
@section('content')
    <div id="wrapper">
        <form action="/sign-in" id="signin" method="POST">
            <div class="w-100" style="text-align: center">
                <h1 class="mb-5" style="color:white">Đăng nhập</h1>
            </div>
            <div class="account-group">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" class="account-input" placeholder="Email/Tên đăng nhập" value="{{ old('username') }}">
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
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary account-btn mt-3">Đăng nhập</button>
            </div>
            @csrf
            <div class="mt-3" style="text-align: center">
                <span style="color: #d3d3d3">Bạn chưa có tài khoản ? <a href="/sign-up">Đăng ký</a></span>
                <a class="d-block mt-2" href="/retrieval">Quên mật khẩu</a>
            </div>
        </form>
    </div>
@endsection
