<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $tableChart = [];

        $total_amount = 0;
        $total_amount_new = 0;
        $total_amount_old = 0;

        $users = $this->userRepository->get([WhereClause::query('status', 1)]);

        // Đơn hàng tháng
        $orderNews = $this->repository->get([
            WhereClause::queryMonth('date_created', date("m")),
            WhereClause::queryYear('date_created', date("Y")),
            WhereClause::query('order_status', "Hoàn thành")
        ]);

        // Đơn hàng tháng cũ
        if(date("m") == 1) {
            $orderOlds = $this->repository->get([
                WhereClause::queryMonth('date_created', 12),
                WhereClause::queryYear('date_created', date("Y")-1),
                WhereClause::query('order_status', "Hoàn thành")
            ]);
        } else {
            $orderOlds = $this->repository->get([
                WhereClause::queryMonth('date_created', date("m")-1),
                WhereClause::queryYear('date_created', date("Y")),
                WhereClause::query('order_status', "Hoàn thành")
            ]);
        }

        //  Thông tin đơn hàng tháng
        if (count($orderNews) > 0) {
            foreach ($orderNews as $order) {
                $total_amount_new += $order->total_amount;
            }
        }

        // Thông tin đơn hàng tháng cũ
        if (count($orderOlds) > 0) {
            foreach ($orderOlds as $order) {
                $total_amount_old += $order->total_amount;
            }
            $textPercent = ceil((($total_amount_new - $total_amount_old) / $total_amount_old) * 100);
            $count = ceil(((count($orderNews) - count($orderOlds)) / count($orderNews)) * 100);
        } else {
            $textPercent = 0;
            $count = 0;
        }

        // Bảng
        $products = $this->warehouseRepository->paginate(10, [WhereClause::query('quantity', '50', '<=')], 'quantity:asc', ['products', 'sizes', 'colors']);
        foreach ($products as $p) {
            array_push($tableProduct, $p);
        }

        $order = $this->repository->paginate(10, [WhereClause::query('order_status', 'Lên đơn')], 'date_created:desc');
        foreach ($order as $o) {
            array_push($tableOrder, $o);
        }

        $orderYears = $this->repository->get([
            WhereClause::queryYear('date_created', date("Y")),
            WhereClause::query('order_status', "Hoàn thành")
        ]);

        if (count($orderYears) > 0) {
            foreach ($orderYears as $order) {
                $total_amount += $order->total_amount;
            }

            for($i = 1; $i <= 12; $i++) {
                $amount_unit = 0;
                $orderUnit = $this->repository->get([
                    WhereClause::queryMonth('date_created', $i),
                    WhereClause::queryYear('date_created', date("Y")),
                    WhereClause::query('order_status', "Hoàn thành")
                ]);

                if(count($orderUnit) > 0){
                    foreach($orderUnit as $o) {
                        $amount_unit += $o->total_amount;
                    }
                    $percent = ceil((($amount_unit / $total_amount)) * 100);
                } else {
                    $percent = 0;
                }

                array_push($tableChart,[
                    'mount' => 'Tháng '.$i,
                    'percent' => $percent
                ]);
            }
        }

        $data = [
            'boxes' => [
                [
                    'bg' => 'bg-red',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng trong tháng '.date("m"),
                    'value' => count($orderNews),
                    'note' => $count,
                ],
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa fa-money',
                    'text' => 'Doanh thu trong tháng '.date("m"),
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
            'charts' => $tableChart,
        ];

        return $this->success($data);
    }
}
