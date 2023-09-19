<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PaymentTransactionRepositoryInterface;
use Illuminate\Http\Request;

class PaymentTransactionController extends RestController
{
    protected $postRepository;

    public function __construct(PaymentTransactionRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['order'];
        $withCount = [];
        $orderBy = $request->input('orderBy');

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::orQuery([
                WhereClause::queryLike('name', $request->search),
                WhereClause::queryLike('order_code', $request->search),
                WhereClause::queryRelationHas('order', function ($q) use ($search) {
                    $q->where('customer_name', 'like', '%'.$search.'%');
                })
            ]));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }
}
