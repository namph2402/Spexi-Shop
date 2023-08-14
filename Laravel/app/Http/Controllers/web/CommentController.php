<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommentController extends RestController
{
    public function __construct(CommentRepositoryInterface $repository)
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

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
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
            'content' => 'required',
            'rating' => 'required|numeric',
            'article_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'content',
            'rating',
            'article_id'
        ]);
        $attributes['user_id'] = Auth::user()->id;
        try {
            DB::beginTransaction();
            $this->repository->create($attributes);
            DB::commit();
            return $this->successViewBack('Đánh giá sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Đánh giá sản phẩm thất bại');
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFoundView();
        }
        try {
            DB::beginTransaction();
            $this->repository->delete($id);
            DB::commit();
            return $this->successView(null,'Xóa đánh giá sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Xóa đánh giá sản phẩm thất bại');
        }
    }

}
