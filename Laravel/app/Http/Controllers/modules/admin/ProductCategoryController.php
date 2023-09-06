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
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('parent')) {
            $category = $this->repository->get([WhereClause::query('parent_id', 0), WhereClause::queryDiff('id', $request->parent)]);
            if ($category) {
                foreach ($category as $c) {
                    array_push($categoryArr, $c->id);
                }
                if (count($categoryArr) > 0) {
                    array_push($clauses, WhereClause::queryIn('id', $categoryArr));
                }
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
            'name' => 'required|max:255',
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
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
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
            'name' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'parent_id'
        ]);
        $attributes['slug'] = Str::slug($attributes['name']);
        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name')), WhereClause::queryDiff('id', $model->id)]);

        if ($test_name) {
            return $this->errorHad($request->input('name'));
        }

        if (count($model->childrens) > 0 && $request->parent_id > 0) {
            return $this->errorClient('Danh mục này đang là danh mục cha');
        }

        if ($request->file('image') != '') {
            $image = FileStorageUtil::putFile('product_category_image', $request->file('image'));
            $attributes['image'] = $image;
            FileStorageUtil::deleteFiles($image_old);
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            $this->productRepository->bulkUpdate([WhereClause::query('category_id', $id)], [
                'category_slug' => $attributes['slug']
            ]);
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
        $model = $this->repository->findById($id, [], 'childrens');
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>'), WhereClause::query('parent_id', 0)], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($id);
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
}
