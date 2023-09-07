<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\OrderRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends RestController
{
    protected $userRepository;
    protected $warehouseRepository;

    public function __construct(
        OrderRepositoryInterface     $repository,
        WarehouseRepositoryInterface $warehouseRepository
    ){
        parent::__construct($repository);
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(Request $request)
    {
        $tableProduct = [];
        $tableOrder = [];

        // Đơn hàng mới
        $order = $this->repository->paginate(10, [WhereClause::query('order_status', 'Lên đơn')], 'date_created:desc');
        foreach ($order as $o) {
            array_push($tableOrder, $o);
        }

        // Sản phẩm sắp hết
        $products = $this->warehouseRepository->paginate(10, [WhereClause::query('quantity', '50', '<='),WhereClause::query('status', '1')], 'quantity:asc', ['product', 'size', 'color']);
        foreach ($products as $p) {
            array_push($tableProduct, $p);
        }

        // Dữ liệu
        $orderNew = $this->repository->get([WhereClause::query('order_status', 'Lên đơn'), WhereClause::queryMonth('date_created', date("m")), WhereClause::queryYear('date_created', date("Y"))]);

        $orderShip = $this->repository->get([WhereClause::query('order_status', 'Đang giao'), WhereClause::queryMonth('date_created', date("m")), WhereClause::queryYear('date_created', date("Y"))]);

        $orderDone = $this->repository->get([WhereClause::query('order_status', 'Hoàn thành'), WhereClause::queryMonth('date_created', date("m")), WhereClause::queryYear('date_created', date("Y"))]);

        $orderError = $this->repository->get([WhereClause::query('order_status', 'Hủy đơn'), WhereClause::queryMonth('date_created', date("m")), WhereClause::queryYear('date_created', date("Y"))]);

        $data = [
            'boxes' => [
                [
                    'bg' => 'bg-yellow',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng mới',
                    'value' => count($orderNew),
                ],
                [
                    'bg' => 'bg-blue',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng đang giao',
                    'value' => count($orderShip),
                ],
                [
                    'bg' => 'bg-green',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng hoàn thành',
                    'value' => count($orderDone),
                ],
                [
                    'bg' => 'bg-red',
                    'icon' => 'fa fa-shopping-cart',
                    'text' => 'Đơn hàng hủy',
                    'value' => count($orderError),
                ]
            ],
            'products' => $tableProduct,
            'orders' => $tableOrder,
        ];

        return $this->success($data);
    }
}
