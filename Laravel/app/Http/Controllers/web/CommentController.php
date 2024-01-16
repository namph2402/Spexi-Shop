<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends RestController
{
    public function __construct(CommentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        $attributes = $request->only([
            'content',
            'rating',
            'article_id'
        ]);
        $attributes['user_id'] = Auth::user()->id;

        try {
            $this->repository->create($attributes);
            return $this->successViewBack('Đánh giá sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorView('Đánh giá sản phẩm thất bại');
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return $this->successView(null,'Xóa đánh giá sản phẩm thành công');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorView('Xóa đánh giá sản phẩm thất bại');
        }
    }

}
