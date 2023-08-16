<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;

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

        $total_amount_new = 0;
        $total_amount_old = 0;

        $orderNews = $this->repository->get([
            WhereClause::queryMonth('date_created', date("m")),
            WhereClause::queryYear('date_created', date("Y")),
            WhereClause::query('order_status', "Hoàn thành")
        ]);

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

        if (count($orderNews) > 0) {
            foreach ($orderNews as $order) {
                $total_amount_new = $total_amount_new + $order->amount;
            }
        }

        if (count($orderOlds) > 0) {
            foreach ($orderOlds as $order) {
                $total_amount_old = $total_amount_old + $order->amount;
            }
        }

        $users = $this->userRepository->get([WhereClause::query('status', 1)]);

        $warehouses = $this->warehouseRepository->paginate(10, [WhereClause::query('quantity', '50', '<=')], 'quantity:asc', ['products', 'sizes', 'colors']);
        foreach ($warehouses as $w) {
            array_push($tableProduct, $w);
        }

        $order = $this->repository->paginate(10, [WhereClause::query('order_status', 'Lên đơn')], 'date_created:desc');
        foreach ($order as $o) {
            array_push($tableOrder, $o);
        }

        if(count($orderOlds) > 0) {
            $textPercent = ceil((($total_amount_new - $total_amount_old) / $total_amount_old) * 100);
            $count = ceil(((count($orderNews) - count($orderOlds)) / count($orderNews)) * 100);
        } else {
            $textPercent = 0;
            $count = 0;
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
        ];

        return $this->success($data);
    }
}
