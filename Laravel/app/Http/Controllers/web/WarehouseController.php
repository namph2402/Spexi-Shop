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
        $validator = $this->validateRequest($request, [
            'product_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'color_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $table = $this->repository->find([
            WhereClause::query('product_id', $request->product_id),
            WhereClause::query('size_id', $request->size_id),
            WhereClause::query('color_id', $request->color_id),
            WhereClause::query('status', 1)
        ]);

        if (empty($table)) {
            return $this->success(['quantity' => 0]);
        } else {
            return $this->success(['quantity' => $table->quantity]);
        }
    }
}
