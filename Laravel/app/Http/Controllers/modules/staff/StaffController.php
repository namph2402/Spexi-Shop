<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\StaffRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StaffController extends RestController
{
    public function __construct(StaffRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:desc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([
                WhereClause::queryLike('username', $request->search),
                WhereClause::queryLike('fullname', $request->search),
                WhereClause::queryLike('phone', $request->search),
            ]));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($request->has('position')) {
            array_push($clauses, WhereClause::query('position', $request->position));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'username' => 'required|max:255',
            'fullname' => 'required|max:255',
            'phone' => 'required|numeric',
            'dob' => 'required|max:255',
            'gender' => 'required|max:255',
            'address' => 'required|max:255',
            'wage' => 'numeric',
            'position' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'username',
            'fullname',
            'phone',
            'dob',
            'gender',
            'address',
            'wage',
            'position',
            'bank_name',
            'bank_number',
        ]);

        $attributes['password'] = Hash::make('123456a@');
        $attributes['remember_token'] = Str::random(100);

        if($request->gender == 0) {
            $attributes['avatar'] = 'http://localhost:8000/assets/img/private/man.webp';
        } else {
            $attributes['avatar'] = 'http://localhost:8000/assets/img/private/woman.webp';
        };

        $test_name = $this->repository->find([WhereClause::query('username', $request->input('username'))]);
        if ($test_name) {
            return $this->errorHad($request->input('username'));
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'fullname' => 'nullable|max:255',
            'phone' => 'nullable|numeric',
            'dob' => 'nullable|max:255',
            'gender' => 'nullable|max:255',
            'address' => 'nullable|max:255',
            'position' => 'nullable|max:255',
            'wage' => 'numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'fullname',
            'phone',
            'dob',
            'gender',
            'address',
            'wage',
            'position',
            'bank_name',
            'bank_number',
        ]);

        if($request->gender == 0) {
            $attributes['avatar'] = 'http://localhost:8000/assets/img/private/man.webp';
        } else {
            $attributes['avatar'] = 'http://localhost:8000/assets/img/private/woman.webp';
        };

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

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

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, ['status' => true]);
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
            $model = $this->repository->update($id, ['status' => false]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function repassword($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id,[
                'password' => Hash::make('123456a@'),
                'remember_token' => Str::random(100),
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

}
