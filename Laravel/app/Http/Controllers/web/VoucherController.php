<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\VoucherRepositoryInterface;
use Illuminate\Http\Request;

class VoucherController extends RestController
{
    public function __construct(VoucherRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $date = Date("Y-m-d");
        $clauses = [WhereClause::query('status',1), WhereClause::query('private',0), WhereClause::queryDate('expired_date', $date, '>=')];
        $with = [];
        $withCount = [];
        $orderBy = 'id:desc';
        
        $vouchers = $this->repository->paginate(10, $clauses, $orderBy, $with, $withCount);

        return view('profile.voucher', compact('vouchers'));
    }

    public function check($code)
    {
        $data = $this->repository->find([
            WhereClause::query('code', $code),
            WhereClause::query('status', 1),
            WhereClause::query('remain_quantity', '0', '>'),
            WhereClause::queryDate('expired_date', date("Y-m-d"), '>=')
        ]);

        $arrVoucher = [];
        if ($data != null) {
            $arrVoucher = [
                'id' => $data->id,
                'type' => $data->type,
                'min_order_value' => $data->min_order_value,
                'discount_value' => $data->discount_value,
                'discount_percent' => $data->discount_percent
            ];
        }

        return $this->success($arrVoucher);
    }
}
