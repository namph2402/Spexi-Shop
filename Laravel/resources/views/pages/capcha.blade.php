@extends('components.layout')
@section('title')
    Mã xác nhận
@endsection
@section('content')
    <div id="wrapper">
        <form action="/check-capcha" id="capcha" name="capcha" method="POST">
            <div class="w-100" style="text-align: center">
                <h2 class="m-0" style="color:white">Mã xác nhận</h2>
            </div>
            <div class="text-center mb-4">
                <span class="d-block">Mã xác nhận sẽ được gửi về email của bạn</span>
            </div>
            <div class="login-group form-capcha mb-4">
                <i class="fas fa-user"></i>
                <input type="number" name="code" class="login-input" placeholder="Nhập mã"
                       value="{{ old('code') }}" inputmode="numeric">
                <input type="email" name="email" value="{{ session('email') }}" hidden>
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary btn-login mt-3">Xác nhận</button>
            </div>
            @csrf
            <div class="mt-3" style="text-align: center">
                <a href="/sign-up">Gửi lại mã</a>
            </div>
        </form>
    </div>
@endsection
