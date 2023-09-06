<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends RestController
{
    protected $userRepository;

    public function __construct(UserProfileRepositoryInterface $repository, UserRepositoryInterface $userRepository)
    {
        parent::__construct($repository);
        $this->userRepository = $userRepository;
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
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $this->repository->delete($id, ['account']);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $model = $this->userRepository->update($model->user_id, ['status' => true]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $model = $this->userRepository->update($model->user_id, ['status' => false]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
