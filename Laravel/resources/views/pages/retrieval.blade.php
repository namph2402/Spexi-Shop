@extends('components.layout')
@section('title')
    Đăng nhập
@endsection
@section('content')
    <div id="wrapper">
        <form action="/retrieval" id="retrieval" name="retrieval" method="POST">
            <div class="w-100" style="text-align: center">
                <h2 class="mb-5" style="color:white">Lấy lại mật khẩu</h2>
            </div>
            <div class="account-group mb-4">
                <i class="fas fa-user"></i>
                <input type="text" name="user" class="account-input" placeholder="Email/Tên đăng nhập" value="{{ old('user') }}">
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary account-btn mt-3">Lấy mật khẩu</button>
            </div>
            <div class="mt-3" style="text-align: center">
                <span style="color: #d3d3d3">Bạn đã có tài khoản ? <a href="/sign-in">Đăng nhập</a></span>
            </div>
            @csrf
        </form>
    </div>
@endsection
