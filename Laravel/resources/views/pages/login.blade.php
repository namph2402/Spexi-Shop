@extends('components.layout')
@section('title')
    Đăng nhập
@endsection
@section('content')
    <div id="wrapper">
        <form action="/sign-in" id="login" name="login" method="POST">
            <div class="w-100" style="text-align: center">
                <h1 class="mb-5" style="color:white">Đăng nhập</h1>
            </div>
            <div class="login-group mb-4">
                <i class="fas fa-user"></i>
                <input type="text" name="username" class="login-input" placeholder="Tên đăng nhập/Email"
                       value="{{ old('username') }}">
            </div>
            <div class="login-group">
                <i class="fas fa-key"></i>
                <input type="password" name="password" class="login-input" placeholder="Mật khẩu"
                       value="{{ old('password') }}">
                <div id="eye">
                    <i class="far fa-eye"></i>
                </div>
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary btn-login mt-3">Đăng nhập</button>
            </div>
            @csrf
            <div class="mt-3" style="text-align: center">
                <span style="color: #d3d3d3">Bạn chưa có tài khoản? <a href="/sign-up">Đăng ký</a></span>
                <a class="d-block mt-2" href="/retrieval">Quên mật khẩu ?</a>
            </div>
        </form>
    </div>
@endsection
