<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <style>
        p {
            margin-block-start: 5px;
            margin-block-end: 5px;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
        }

        .col-10 {
            width: 10%;
        }

        .col-20 {
            width: 20%;
        }

        .col-30 {
            width: 30%;
        }

        .col-40 {
            width: 40%;
        }

        .col-50 {
            width: 50%;
        }

        .text-center {
            text-align: center;
        }

        html,
        body {
            width: 100%;
            margin: 0;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
        }

        .page {
            width: 98%;
            margin: 0 auto;
        }

        .page table {
            width: 100%;
        }

        .page table,
        .page th,
        .page tr,
        .page td {
            border: 2px dashed black;
            padding: 10px;
        }

        .product-list table {
            width: 100%;
        }

        .product-list table,
        .product-list tr,
        .product-list th,
        .product-list td {
            padding: 5px;
            border: 1px solid black;
        }

        .product-list table tr td.column-1 {
            width: 10%;
            text-align: center;
        }

        .product-list table tr td.column-2 {
            width: 70%;
            text-align: left;
        }

        .product-list table tr td.column-3 {
            width: 20%;
            text-align: center;
        }

        .barcode {
            max-width: 100% !important;
        }

        .cod-fee {
            font-size: 30px;
            font-weight: bold;
        }

        @page {
            size: A5;

        }
    </style>
</head>

<body class="A5">
    @foreach ($orders as $order)
        <div class="page sheet padding-10mm">
            <table>
                <tbody>
                    <tr>
                        <td class="col-50 text-center">
                            <p style="display:flex; height: 63px">
                                <img alt="Company Logo" style="margin: auto; width: 120px;" src="{{ $dataStoreOrder['logo'] }}">
                            </p>
                            <h2>{{ $dataStoreOrder['name'] ?? 'Chưa cấu hình' }}</h2>
                        </td>
                        <td class="col-50 text-center">
                            <p style="display:flex; height: 63px">
                                <img alt="Logistics Logo" style="margin: auto; width: 120px;" src="{{ $order->shipping->unit->logo}}">
                            </p>
                            <h2>{{ $order->shipping->unit->name }}</h2>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <svg class="barcode"
                                jsbarcode-format="{{ $dataStoreOrder['barcodeType'] }}"
                                jsbarcode-value="{{ $order->shipping->code }}"
                                jsbarcode-textmargin="0"
                                jsbarcode-fontoptions="bold">
                            </svg>
                        </td>
                    </tr>
                    @if ($order->shipping->unit->name == \App\Common\Enum\UnitName::TIET_KIEM)
                        <tr>
                            <td colspan="2" class="text-center">
                                <h2 style="font-size: 40px;margin: 3px;">
                                    {{ \App\Utils\Logistics\GiaoHangTietKiemUtil::getLocationFromCode($order->shipping->code) }}
                                </h2>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="col-50">
                            <p>Từ</p>
                            <p><strong>{{ $order->shipping->store->name ?? ($dataStoreOrder['name'] ?? 'Chưa cấu hình') }}</strong>
                            </p>
                            <p>{{ $order->shipping->store->data['address'] ?? ($dataStoreOrder['address'] ?? 'Chưa cấu hình') }}
                            </p>
                            <p>{{ $order->shipping->store->data['phone'] ?? ($dataStoreOrder['phone'] ?? 'Chưa cấu hình') }}
                            </p>
                        </td>
                        <td class="col-50">
                            <p>Đến</p>
                            <p><strong>{{ $order->customer_name }}</strong></p>
                            <p>{{ join(', ', [$order->customer_address, $order->ward, $order->district, $order->province]) }}
                            </p>
                            @php
                                $phone = '******' . substr($order->customer_phone, 6, 4);
                            @endphp
                            <p>{{ $phone }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="product-list" colspan="2">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 20%">Mã sản phẩm</th>
                                        <th style="width: 40%">Tên sản phẩm</th>
                                        <th style="width: 20%">Loại</th>
                                        <th style="width: 10%">SL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->details as $index => $d)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-left">{{ $d->product_code }}</td>
                                            <td class="text-lef">{{ $d->product_name }}</td>
                                            <td class="text-left">{{ $d->size }}, {{ $d->color }}</td>
                                            <td class="text-center">{{ $d->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Tiền thu người nhận: <strong class="cod-fee">{{ number_format($order->cod_fee) }}
                                VND</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>
                                <u>Ghi chú: </u>
                                <span>{{ $order->shipping->service->data['default_note'] ?? 'Không giao được liên lạc lại shop. Cho khách xem hàng, không cho thử.' }}</span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            JsBarcode(".barcode").init();
            setTimeout(() => {
                window.print();
            }, 2000);
        });
    </script>
</body>

</html>
