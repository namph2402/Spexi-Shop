<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductCategoryController extends RestController
{
    protected $productRepository;

    public function __construct(ProductCategoryRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['parent'];
        $withCount = [];
        $categoryArr = [];
        $orderBy = $request->input('orderBy', 'parent_id:asc,order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('parent')) {
            $category = $this->repository->pluck([WhereClause::query('parent_id', 0), WhereClause::queryDiff('id', $request->parent)], 'id');
            if (count($category) > 0) {
                array_push($clauses, WhereClause::queryIn('id', $category));
            }
        }

        if ($request->has('child')) {
            $category = $this->repository->get([], null, [], ['childrens']);
            if ($category) {
                foreach ($category as $c) {
                    if ($c->childrens_count == 0) {
                        array_push($categoryArr, $c->id);
                    }
                }
                array_push($clauses, WhereClause::queryIn('id', $categoryArr));
            }
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
            'name' => 'required|max:255|unique:product_categories',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'parent_id',
        ]);
        $attributes['slug'] = Str::slug($attributes['name']);

        if ($request->hasFile('image')) {
            $image = FileStorageUtil::putFile('product_category_image', $request->file('image'));
            $attributes['image'] = $image;
        }

        $lastItem = $this->repository->find([WhereClause::query('parent_id', $request->parent_id)], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        try {
            $model = $this->repository->create($attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            if (isset($attributes['image'])) {
                FileStorageUtil::deleteFiles($image);
            }
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id, ['childrens']);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $image_old = $model->image;

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255|unique:product_categories,name,' . $id,
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
        ]);
        $attributes['slug'] = Str::slug($attributes['name']);

        if ($request->file('image') != '') {
            $image = FileStorageUtil::putFile('product_category_image', $request->file('image'));
            $attributes['image'] = $image;
        }

        try {
            $model = $this->repository->update($id, $attributes);
            $this->productRepository->bulkUpdate([WhereClause::query('category_id', $id)], [
                'category_slug' => $attributes['slug']
            ]);
            if ($request->file('image') != '') {
                FileStorageUtil::deleteFiles($image_old);
            }
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            if ($request->file('image') != '') {
                FileStorageUtil::deleteFiles($image);
            }
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        $image = $model->image;
        $order = $model->order;
        $group = $model->parent_id;

        try {
            $this->repository->delete($id);
            $this->repository->bulkUpdate([WhereClause::query('order', $order, '>'), WhereClause::query('parent_id', $group)], ['order' => DB::raw('`order` - 1')]);
            FileStorageUtil::deleteFiles($image);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<'), WhereClause::query('parent_id', $model->parent_id)], 'order:desc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }

        try {
            $order = $model->order;
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = $this->repository->findById($id);

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>'), WhereClause::query('parent_id', $model->parent_id)], 'order:asc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }

        try {
            $order = $model->order;
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
   }
}