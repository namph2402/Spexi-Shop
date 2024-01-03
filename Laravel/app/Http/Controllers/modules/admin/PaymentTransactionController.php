<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PaymentTransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $orderBy = $request->input('orderBy','id:desc');

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::orQuery([
                WhereClause::queryLike('name', $search),
                WhereClause::queryLike('order_code', $search),
                WhereClause::queryLike('creator_name', $search),
                WhereClause::queryRelationHas('order', function ($q) use ($search) {
                    $q->where('customer_phone', 'like', '%'.$search.'%');
                })
            ]));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($request->has('type')) {
            array_push($clauses, WhereClause::query('type', $request->type));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($id);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
