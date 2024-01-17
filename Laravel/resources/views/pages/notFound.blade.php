@extends('components.layout')
@section('title')
    Không tìm thấy nội dung
@endsection
@section('content')
    <div class="row px-xl-5">
        <div class="col-lg-12 table-responsive mb-5">
            <div style="text-align:center">
                <h3 class="mb-30">Không tìm thấy nội dung tìm kiếm <img class="img-icon"
                        src="{{ asset('assets/img/icon/icon.png') }}"></h3>
                <div>
                    <a href="/products" class="btn btn-primary">Tiếp tục mua hàng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
