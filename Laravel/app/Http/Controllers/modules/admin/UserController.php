<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\CartRepositoryInterface;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends RestController
{
    protected $userRepository;
    protected $cartRepository;

    public function __construct(UserProfileRepositoryInterface $repository, UserRepositoryInterface $userRepository, CartRepositoryInterface $cartRepository)
    {
        parent::__construct($repository);
        $this->userRepository = $userRepository;
        $this->cartRepository = $cartRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['account'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:desc');

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::orQuery([
                WhereClause::queryLike('fullname', $request->search),
                WhereClause::queryLike('phone', $request->search),
                WhereClause::queryRelationHas('account', function ($q) use ($search) {
                    $q->where('email', $search);
                }),
                WhereClause::queryRelationHas('account', function ($q) use ($search) {
                    $q->where('username', $search);
                }),
            ]));
        }

        if ($request->has('phoneOrder')) {
            array_push($clauses, WhereClause::query('phone', $request->phoneOrder));
        }

        if ($request->has('status')) {
            $status = $request->status;
            array_push($clauses, WhereClause::queryRelationHas('account', function ($q) use ($status) {
                $q->where('status', $status);
            }));
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
        $model = $this->repository->findById($id);

        $cart = $this->cartRepository->find([WhereClause::query('user_id', $model->user_id)]);
        try {
            $this->repository->delete($id, ['account']);
            if($cart) {
                $this->cartRepository->delete($cart->id, ['items']);
            }
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);

        try {
            $model = $this->userRepository->update($model->user_id, ['status' => true]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = $this->repository->findById($id);

        try {
            $model = $this->userRepository->update($model->user_id, ['status' => false]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

}