@extends('components.layout')
@section('title')
    Mã xác nhận
@endsection
@section('content')
    <div id="wrapper">
        <form action="/check-capcha" id="capcha" name="capcha" method="POST">
            <div class="w-100" style="text-align: center">
                <h2 class="mb-3" style="color:white">Mã xác nhận</h2>
            </div>
            <div class="text-center mb-4">
                <span class="d-block" style="color: #d3d3d3">Mã xác nhận sẽ được gửi về email của bạn</span>
            </div>
            <div class="login-group form-capcha mb-4">
                <i class="fas fa-user"></i>
                <input type="number" name="code" class="login-input" placeholder="Nhập mã"
                       value="{{ old('code') }}" inputmode="numeric">
                <input type="email" name="email" id="emailCapcha" value="{{ $email }}" hidden>
                @if (isset($msg))
                <span class="err-text" style="top: 35px">{{$msg}}</span>
                @endif
            </div>
            <div style="text-align: center">
                <button type="submit" class="btn btn-primary btn-login mt-3">Xác nhận</button>
            </div>
            @csrf
            <div class="mt-3" style="text-align: center">
                @php
                $url = config('app.url')
                @endphp
                <span class="d-flex justify-content-center" id="timeCode" style="color: #d3d3d3">Gửi lại mã xác nhận sau <p class="ml-1" id="timeSecond">40s</p></span>
                <a id="sendCode" class="btn-send" onclick="sendCapcha('{!! $url !!}')">Gửi lại mã xác nhận</a>
            </div>
        </form>
    </div>
@endsection
