@extends('profile.profile')
@section('title')
    Đơn hàng
@endsection
@section('content-child')
    <div class="col-lg-9">
        <div class="row">
            <div class="col-12 p-0">
                <nav class="breadcrumb bg-light mb-3">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Đơn hàng</span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 py-4 mb-3" style="background: white">
                <table class="table table-primary table-bordered" style="background:white">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 15%">Mã đơn</th>
                            <th style="text-align: center; width: 55%">Sản phẩm</th>
                            <th style="text-align: center; width: 15%">Tổng tiền</th>
                            <th style="text-align: center; width: 15%">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $item)
                            <tr>
                                <th style="text-align: center">
                                    {{ $item->code }}
                                    <a class="d-block mt-2" href="/order/detail/{{ $item->id }}">Xem</a>
                                </th>
                                <td>
                                    <table class="w-100">
                                        <tbody>
                                            @foreach ($item->details as $key => $d)
                                                <tr>
                                                    <td style="width:20%">
                                                        <img class="w-100" data-src="{{ $d->product->image }}" src="{{ $d->product->image }}" alt="{{ $d->product_name }}">
                                                    </td>
                                                    <td style="width:60%">
                                                        <span class="text-product">{{ $d->product_name }}</span>
                                                    </td>
                                                    <td style="width:20%">{{ $d->size }}, {{ $d->color }}</td>
                                                </tr>
                                                @if ($key == 2)
                                                @break
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <th style="text-align: center; padding: 10px 0 0">
                                {{ number_format($item->total_amount, 0, '.', '.') }} đ</th>
                            <td style="text-align: center; padding: 10px 0 0">{{ $item->order_status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <nav class="w-100">
            <ul class="pagination justify-content-center">
                @if ($orders->previousPageUrl())
                    <li class="page-item">
                        <a class="page-link" style="cursor:pointer"
                            onclick="setParamsPage('page','{{ (int) Request::get('page') - 1 }}')">Trước</a>
                    </li>
                @else
                    <li class="page-item disabled"><a class="page-link">Trước</a></li>
                @endif
                @for ($i = 1; $i <= $orders->lastPage(); $i++)
                    @if ($orders->currentPage() == $i)
                        <li class="page-item active">
                            <a class="page-link">{{ $i }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" style="cursor:pointer"
                                onclick="setParamsPage('page','{{ $i }}')">{{ $i }}</a>
                        </li>
                    @endif
                @endfor
                @if ($orders->nextPageUrl())
                    <li class="page-item">
                        @if ((int) Request::get('page') == 0)
                            <a class="page-link" style="cursor:pointer"
                                onclick="setParamsPage('page','{{ (int) Request::get('page') + 2 }}')">Sau</a>
                        @else
                            <a class="page-link" style="cursor:pointer"
                                onclick="setParamsPage('page','{{ (int) Request::get('page') + 1 }}')">Sau</a>
                        @endif
                    </li>
                @else
                    <li class="page-item disabled"><a class="page-link">Sau</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endsection
