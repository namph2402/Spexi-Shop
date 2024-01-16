<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;

class WarehouseController extends RestController
{

    public function __construct(
        WarehouseRepositoryInterface $repository
    )
    {
        parent::__construct($repository);
    }

    public function getWarehouse(Request $request)
    {
        $table = $this->repository->find([
            WhereClause::query('product_id', $request->product_id),
            WhereClause::query('size_id', $request->size_id),
            WhereClause::query('color_id', $request->color_id),
            WhereClause::query('status', 1)
        ]);

        return $this->success(['quantity' => $table ? $table->quantity : 0]);
    }
}
