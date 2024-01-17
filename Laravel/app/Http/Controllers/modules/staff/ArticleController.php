<?php

namespace App\Http\Controllers\modules\staff;

use App\Http\Controllers\RestController;
use App\Repository\ArticleRepositoryInterface;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends RestController
{
    public function __construct(ArticleRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        $user = AuthUtil::getInstance()->getModel();

        $validator = $this->validateRequest($request, [
            'content' => 'required',
            'articleable_type' => 'required',
            'articleable_id' => 'required|numeric'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'articleable_id',
            'articleable_type',
            'content'
        ]);
        $attributes['author_name'] = $user->name;

        try {
            $model = $this->repository->create($attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, [
            'content' => 'nullable',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'articleable_type',
            'articleable_id',
            'content'
        ]);

        try {
            $model = $this->repository->update($id, $attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }
}
