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
        $total_amount = 0;
        $orders = $this->repository->get([WhereClause::query('date_created', date('Y-m-d'))]);
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $total_amount = $total_amount + $order->amount;
            }
        }
        $users = $this->userRepository->get([WhereClause::query('status', 1)]);

        $tableData = [];

        $warehouses = $this->warehouseRepository->paginate(10, [WhereClause::query('quantity', '10', '<')], 'product_id:asc', ['products', 'sizes', 'colors']);

        foreach ($warehouses as $row) {
            array_push($tableData, [
                [
                    'value' => $row->code,
                    'align' => 'text-left'
                ],
                [
                    'value' => $row->products->name,
                    'align' => 'text-left'
                ],
                [
                    'value' => $row->quantity,
                    'align' => 'text-right'
                ],
            ]);
        }


        $data = [
            'boxes' => [
                [
                    'bg' => 'bg-red',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng hôm nay',
                    'value' => count($orders)
                ],
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa fa-money',
                    'text' => 'Doanh thu hôm nay',
                    'value' => number_format($total_amount, 0, '.', ',')
                ],
                [
                    'bg' => 'bg-green',
                    'icon' => 'fa fa-user',
                    'text' => 'Số lượng khách hàng',
                    'value' => count($users)
                ]
            ],

            'tables' => [
                [
                    'bg' => 'box-warning',
                    'title' => 'Sản phẩm sắp hết hàng',
                    'label' => 'Tất cả',
                    'rows' => $tableData
                ],
            ]
        ];

        return $this->success($data);
    }
}
