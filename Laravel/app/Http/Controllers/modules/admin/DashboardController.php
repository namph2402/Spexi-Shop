<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use App\Utils\OfficeUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends RestController
{
    protected $userRepository;
    protected $warehouseRepository;

    public function __construct(
        OrderRepositoryInterface     $repository,
        UserRepositoryInterface      $userRepository,
        WarehouseRepositoryInterface $warehouseRepository
    ) {
        parent::__construct($repository);
        $this->userRepository = $userRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(Request $request)
    {
        $tableProduct = [];
        $tableOrder = [];
        $tableProductCodeMain = [];
        $tableProductQuantityMain = [];
        $tablePercent = [];
        $tableQuantity = [];
        $tableUserMain = [];

        $month = $request->input('month', date("m"));
        $year = $request->input('year', date("Y"));

        // Số lượng khách hàng
        $users = $this->userRepository->get([WhereClause::query('status', 1)]);

        // Đơn hàng một tháng
        $orderNews = $this->repository->get([
            WhereClause::queryMonth('date_created', $month),
            WhereClause::queryYear('date_created', $year),
            WhereClause::query('order_status', "Hoàn thành")
        ]);
        $total_amount_new =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereMonth('date_created', '=', $month)->whereYear('date_created', '=', $year)->sum('total_amount');

        // Đơn hàng tháng cũ
        if ($month == 1) {
            $orderOlds = $this->repository->get([
                WhereClause::queryMonth('date_created', 12),
                WhereClause::queryYear('date_created', $year - 1),
                WhereClause::query('order_status', "Hoàn thành")
            ]);
            $total_amount_old =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereMonth('date_created', '=', 12)->whereYear('date_created', '=', $year - 1)->sum('total_amount');
        } else {
            $orderOlds = $this->repository->get([
                WhereClause::queryMonth('date_created', $month - 1),
                WhereClause::queryYear('date_created', $year),
                WhereClause::query('order_status', "Hoàn thành")
            ]);
            $total_amount_old =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereMonth('date_created', '=', $month - 1)->whereYear('date_created', '=', $year)->sum('total_amount');
        }

        // Tính chênh lệch tháng
        if (count($orderOlds) > 0) {
            $textPercent = ceil((($total_amount_new - $total_amount_old) / $total_amount_old) * 100);
            if (count($orderNews) > 0) {
                $count = ceil(((count($orderNews) - count($orderOlds)) / count($orderNews)) * 100);
            } else {
                $count = 0;
            }
        } else {
            $textPercent = 0;
            $count = 0;
        }

        // Sản phẩm sắp hết
        $products = $this->warehouseRepository->paginate(10, [WhereClause::query('quantity', '50', '<=')], 'quantity:asc', ['product', 'sizes', 'colors']);
        foreach ($products as $p) {
            array_push($tableProduct, $p);
        }

        // Đơn hàng mới
        $order = $this->repository->paginate(10, [WhereClause::query('order_status', 'Lên đơn')], 'date_created:desc');
        foreach ($order as $o) {
            array_push($tableOrder, $o);
        }

        // Biểu đồ đơn hàng + doanh thu trong năm
        $orderYears = $this->repository->get([WhereClause::queryYear('date_created', $year), WhereClause::query('order_status', "Hoàn thành")]);
        $total_amount_year =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereYear('date_created', '=', $year)->sum('total_amount');
        if (count($orderYears) > 0) {
            for ($i = 1; $i <= 12; $i++) {
                $amount_unit = 0;
                $orderUnit = $this->repository->get([
                    WhereClause::queryMonth('date_created', $i),
                    WhereClause::queryYear('date_created', $year),
                    WhereClause::query('order_status', "Hoàn thành")
                ]);
                if (count($orderUnit) > 0) {
                    foreach ($orderUnit as $o) {
                        $amount_unit += $o->total_amount;
                    }
                }
                $percent = round((($amount_unit / $total_amount_year)) * 100, 2);
                array_push($tableQuantity, count($orderUnit));
                array_push($tablePercent, $percent);
            }
        }

        // Biểu đồ sản phẩm bán chạy trong tháng
        $productMain = DB::select('SELECT product_code, SUM(quantity) AS quantity FROM order_details WHERE MONTH(created_at) = ' . $month . ' AND YEAR(created_at) = ' . $year . ' GROUP BY product_code ORDER BY SUM(quantity) DESC');
        if (count($productMain) > 0) {
            foreach ($productMain as $key => $p) {
                if ($key < 4) {
                    array_push($tableProductCodeMain, $p->product_code);
                    array_push($tableProductQuantityMain, $p->quantity);
                } else {
                    break;
                }
            };
        }

        // Biểu đồ người dùng tiềm năng trong tháng
        $userMain = DB::select('SELECT customer_name, customer_phone, SUM(total_amount) AS total_amount FROM orders WHERE order_status = "Hoàn thành" AND MONTH(date_created) = ' . $month . ' AND YEAR(date_created) = ' . $year . ' GROUP BY customer_name ORDER BY SUM(total_amount) DESC');
        if (count($userMain) > 0) {
            foreach ($userMain as $key => $u) {
                if ($key < 10) {
                    array_push($tableUserMain, $u);
                } else {
                    break;
                }
            };
        }

        $data = [
            'boxes' => [
                [
                    'bg' => 'bg-red',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng trong tháng ' . $month,
                    'value' => count($orderNews),
                    'note' => $count,
                ],
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa fa-money',
                    'text' => 'Doanh thu trong tháng ' . $month,
                    'value' => number_format($total_amount_new, 0, '.', ','),
                    'note' => $textPercent
                ],
                [
                    'bg' => 'bg-green',
                    'icon' => 'fa fa-user',
                    'text' => 'Số lượng khách hàng',
                    'value' => count($users),
                    'note' => "0",
                ]
            ],
            'products' => $tableProduct,
            'orders' => $tableOrder,
            'percents' => $tablePercent,
            'quantity' => $tableQuantity,
            'productCodeMains' => $tableProductCodeMain,
            'productQuantityMains' => $tableProductQuantityMain,
            'user' => $tableUserMain,
        ];

        return $this->success($data);
    }

    public function export(Request $request)
    {
        $month = $request->input('month', date("m"));
        $year = $request->input('year', date("Y"));

        $xlsx = [
            "Đơn hàng năm " . $year => [['Tháng', 'Số lượng đơn hàng', 'Tỉ lệ (%)', 'Doanh thu đơn hàng', 'Tỉ lệ (%)']],
            "Đơn hàng tháng " . $month => [['Mã đơn', 'Tên người nhận', 'Số điện thoại nhận', 'Địa chỉ nhận hàng', 'Tổng đơn', 'Phí ship', 'Giảm giá', 'Tổng thanh toán', 'Loại thanh toán', 'Thanh toán', 'Trạng thái đơn hàng']],
            "Sản phẩm bán chạy tháng " . $month => [['Mã sản phẩm', 'Tên sản phẩm', 'Giá bán', 'Số lượng bán']],
            "Sản phẩm sắp hết" => [['Mã sản phẩm', 'Tên sản phẩm', 'Giá bán', 'Size', 'Màu', 'Số lượng còn lại']]
        ];

        // Đơn hàng + doanh thu năm
        $orderYear = $this->repository->get([WhereClause::queryYear('date_created', $year), WhereClause::query('order_status', "Hoàn thành")]);
        $totalYear =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereYear('date_created', '=', $year)->sum('total_amount');
        if (count($orderYear) > 0) {
            for ($i = 1; $i <= 12; $i++) {
                $amount_unit = 0;
                $orderUnit = $this->repository->get([WhereClause::queryMonth('date_created', $i), WhereClause::queryYear('date_created', $year), WhereClause::query('order_status', "Hoàn thành")]);
                if (count($orderUnit) > 0) {
                    foreach ($orderUnit as $o) {
                        $amount_unit += $o->total_amount;
                    }
                }
                $percentOrder = round((count($orderUnit) / count($orderYear)) * 100, 2);
                $percentTotal = round((($amount_unit / $totalYear)) * 100, 2);

                array_push($xlsx["Đơn hàng năm " . $year], [
                    'Tháng ' . $i,
                    count($orderUnit),
                    $percentOrder,
                    $amount_unit,
                    $percentTotal
                ]);
            }
            array_push($xlsx["Đơn hàng năm " . $year], [
                'Cả năm',
                count($orderYear),
                '100',
                $totalYear,
                '100'
            ]);
        }

        // Đơn hàng một tháng
        $orderMonth = $this->repository->get([WhereClause::queryMonth('date_created', $month), WhereClause::queryYear('date_created', $year)]);
        if (count($orderMonth)) {
            foreach ($orderMonth as $o) {
                array_push($xlsx["Đơn hàng tháng " . $month], [
                    $o->code,
                    $o->customer_name,
                    $o->customer_phone,
                    $o->customer_text,
                    $o->amount,
                    $o->shipping_fee,
                    $o->discount,
                    $o->total_amount,
                    $o->payment_type,
                    $o->payment_status,
                    $o->order_status,
                ]);
            }
        }

        // Sản phẩm bán chạy một tháng
        $products = DB::select('SELECT product_code, product_name, unit_price, SUM(quantity) AS quantity FROM order_details WHERE MONTH(created_at) = ' . $month . ' AND YEAR(created_at) = ' . $year . ' GROUP BY product_code ORDER BY SUM(quantity) DESC');
        if (count($products)) {
            foreach ($products as $key => $p) {
                if ($key < 20) {
                    array_push($xlsx["Sản phẩm bán chạy tháng " . $month], [
                        $p->product_code,
                        $p->product_name,
                        $p->unit_price,
                        $p->quantity
                    ]);
                } else {
                    break;
                }
            }
        }

        // Sản phẩm sắp hết
        $productOffs = $this->warehouseRepository->get([WhereClause::query('quantity', '50', '<=')], 'quantity:asc', ['product', 'sizes', 'colors']);
        foreach ($productOffs as $p) {
            array_push($xlsx["Sản phẩm sắp hết"], [
                $p->code,
                $p->product->name,
                $p->product->sale_price,
                $p->sizes->name,
                $p->colors->name,
                $p->quantity
            ]);
        }

        try {
            $writer = OfficeUtil::writeXLSX($xlsx);
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="ket_qua_' . time() . '.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
