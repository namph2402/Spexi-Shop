<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends RestController
{
    protected $userRepository;
    protected $warehouseRepository;

    public function __construct(
        OrderRepositoryInterface     $repository,
        UserRepositoryInterface      $userRepository,
        WarehouseRepositoryInterface $warehouseRepository)
    {
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

        //Số lượng khách hàng
        $users = $this->userRepository->get([WhereClause::query('status', 1)]);

        // Đơn hàng tháng này
        $orderNews = $this->repository->get([
            WhereClause::queryMonth('date_created', $month),
            WhereClause::queryYear('date_created', $year),
            WhereClause::query('order_status', "Hoàn thành")
        ]);
        $total_amount_new =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereMonth('date_created', '=', $month)->whereYear('date_created', '=', $year)->sum('total_amount');

        // Đơn hàng tháng cũ
        if($month == 1) {
            $orderOlds = $this->repository->get([
                WhereClause::queryMonth('date_created', 12),
                WhereClause::queryYear('date_created', $year-1),
                WhereClause::query('order_status', "Hoàn thành")
            ]);
            $total_amount_old =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereMonth('date_created', '=', 12)->whereYear('date_created', '=', $year-1)->sum('total_amount');
        } else {
            $orderOlds = $this->repository->get([
                WhereClause::queryMonth('date_created', $month-1),
                WhereClause::queryYear('date_created', $year),
                WhereClause::query('order_status', "Hoàn thành")
            ]);
            $total_amount_old =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereMonth('date_created', '=', $month-1)->whereYear('date_created', '=', $year)->sum('total_amount');
        }

        // Tính chênh lệch tháng
        if (count($orderOlds) > 0) {
            $textPercent = ceil((($total_amount_new - $total_amount_old) / $total_amount_old) * 100);
            if(count($orderNews) > 0) {
                $count = ceil(((count($orderNews) - count($orderOlds)) / count($orderNews)) * 100);
            } else {
                $count = 0;
            }
        } else {
            $textPercent = 0;
            $count = 0;
        }

        // Bảng

        //Sản phẩm sắp hết
        $products = $this->warehouseRepository->paginate(10, [WhereClause::query('quantity', '50', '<=')], 'quantity:asc', ['product', 'sizes', 'colors']);
        foreach ($products as $p) {
            array_push($tableProduct, $p);
        }

        //Đơn hàng mới
        $order = $this->repository->paginate(10, [WhereClause::query('order_status', 'Lên đơn')], 'date_created:desc');
        foreach ($order as $o) {
            array_push($tableOrder, $o);
        }

        //Biểu đồ đơn hàng + doanh thu trong năm
        $orderYears = $this->repository->get([WhereClause::queryYear('date_created', $year), WhereClause::query('order_status', "Hoàn thành")]);
        $total_amount_year =  DB::table('orders')->whereOrderStatus('Hoàn thành')->whereYear('date_created', '=', $year)->sum('total_amount');
        if (count($orderYears) > 0) {
            for($i = 1; $i <= 12; $i++) {
                $amount_unit = 0;
                $orderUnit = $this->repository->get([
                    WhereClause::queryMonth('date_created', $i),
                    WhereClause::queryYear('date_created', $year),
                    WhereClause::query('order_status', "Hoàn thành")
                ]);
                if(count($orderUnit) > 0){
                    foreach($orderUnit as $o) {
                        $amount_unit += $o->total_amount;
                    }
                }
                $percent = round((($amount_unit / $total_amount_year)) * 100, 2);
                array_push($tableQuantity, count($orderUnit));
                array_push($tablePercent,$percent);
            }
        }

        // Biểu đồ sản phẩm bán chạy trong tháng
        $productMain = DB::select('SELECT product_code, SUM(quantity) AS quantity FROM order_details WHERE MONTH(created_at) = '.$month.' AND YEAR(created_at) = '.$year.' GROUP BY product_code ORDER BY SUM(quantity) DESC');
        if(count($productMain) > 0) {
            foreach($productMain as $key => $p) {
                if($key < 4) {
                    array_push($tableProductCodeMain,$p->product_code);
                    array_push($tableProductQuantityMain,$p->quantity);
                } else {
                    break;
                }
            };
        }

        $userMain = DB::select('SELECT customer_name, customer_phone, SUM(total_amount) AS total_amount FROM orders WHERE order_status = "Hoàn thành" AND MONTH(date_created) = '.$month.' AND YEAR(date_created) = '.$year.' GROUP BY customer_name ORDER BY SUM(total_amount) DESC');
        if(count($userMain) > 0) {
            foreach($userMain as $key => $u) {
                if($key < 10) {
                    array_push($tableUserMain,$u);
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
                    'text' => 'Đơn hàng trong tháng '.$month,
                    'value' => count($orderNews),
                    'note' => $count,
                ],
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa fa-money',
                    'text' => 'Doanh thu trong tháng '.$month,
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
}
