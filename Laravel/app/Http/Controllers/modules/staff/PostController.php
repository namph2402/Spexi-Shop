<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ArticleRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Utils\AuthUtil;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends RestController
{
    protected $repository;
    protected $articleRepository;

    public function __construct(PostRepositoryInterface $repository, ArticleRepositoryInterface $articleRepository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['category', 'article', 'tags'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($request->has('category_id')) {
            array_push($clauses, WhereClause::query('category_id', $request->category_id));
        }

        if ($request->has('tag_id')) {
            $tag_id = $request->tag_id;
            array_push($clauses, WhereClause::queryRelationHas('tags', function ($q) use ($tag_id) {
                $q->where('id', $tag_id);
            }));
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
        $user = AuthUtil::getInstance()->getModel();
        $createdImages = [];

        $validator = $this->validateRequest($request, [
            'category_id' => 'required|numeric',
            'category_slug' => 'required|max:255',
            'name' => 'required|max:255',
            'image' => 'required',
            'content' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'category_id',
            'category_slug',
            'name',
            'summary'
        ]);

        $image = FileStorageUtil::putFile('post_image', $request->file('image'));
        array_push($createdImages, $image);
        $attributes['image'] = $image;
        $attributes['slug'] = Str::slug($attributes['name']);

        $lastItem = $this->repository->find([], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name'))]);
        if ($test_name) {
            return $this->errorHad($request->input('name'));
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
            Log::info($user);
            if ($model) {
                $this->articleRepository->create([
                    'content' => $request->content,
                    'author_name' => $user->fullname,
                    'articleable_type' => 'posts',
                    'articleable_id' => $model->id
                ]);
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            FileStorageUtil::deleteFiles($image);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $image_old = $model->image;

        $validator = $this->validateRequest($request, [
            'category_id' => 'nullable|numeric',
            'category_slug' => 'nullable|max:255',
            'name' => 'nullable|max:255',
            'image' => 'nullable',
            'content' => 'nullable',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'category_id',
            'category_slug',
            'name',
            'summary'
        ]);

        if ($request->file('image') != '') {
            $image = FileStorageUtil::putFile('post_image', $request->file('image'));
            $attributes['image'] = $image;
        }

        $attributes['slug'] = Str::slug($attributes['name']);

        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name')), WhereClause::queryDiff('id', $model->id)]);
        if ($test_name) {
            return $this->errorHad($request->input('name'));
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes, ['article']);
            $this->articleRepository->update($model->article->id, [
                'content' => $request->input('content')
            ]);
            DB::commit();
            if ($request->file('image') != '') {
                FileStorageUtil::deleteFiles($image_old);
            }
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            if ($request->file('image') != '') {
                FileStorageUtil::deleteFiles($image);
            }
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $image = $model->image;

        try {
            DB::beginTransaction();
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>')], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($id, ['article']);
            DB::commit();
            FileStorageUtil::deleteFiles($image);
            return $this->success($model);
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

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')], 'order:desc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }

        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')], 'order:asc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }

        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function loadTag(Request $request) {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $postClauses = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('category_id')) {
            array_push($clauses, WhereClause::query('category_id', $request->category_id));
        }

        if ($request->has('tag_id')) {
            $tag_id = $request->tag_id;
            array_push($clauses, WhereClause::queryRelationHas('tags', function ($q) use ($tag_id) {
                $q->where('id', $tag_id);
            }));
        }

        if ($request->has('tag_id_add')) {

            $tagId = $request->tag_id_add;
            $posts = $this->repository->get([WhereClause::queryRelationHas('tags', function ($q) use ($tagId) {
                $q->where('id', $tagId);
            })]);
            if (count($posts) > 0) {
                foreach ($posts as $post) {
                    array_push($postClauses, $post->id);
                }
                array_push($clauses, WhereClause::queryNotIn('id', $postClauses));
            }
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function attachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            foreach ($request->tag_ids as $tagId) {
                $this->repository->attach($model, $tagId);
            };
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

    public function detachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $tagId = $request->tag_ids;

        try {
            DB::beginTransaction();
            $this->repository->detach($model, $tagId);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

}
