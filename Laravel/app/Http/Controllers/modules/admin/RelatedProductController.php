<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductRepositoryInterface;
use App\Repository\RelatedProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RelatedProductController extends RestController
{
    protected $productRepository;

    public function __construct(RelatedProductRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [WhereClause::query('product_id', $request->product_id)];
        $with = ['product'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::queryRelationHas('product', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            }));
        }

        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            array_push($clauses, WhereClause::queryRelationHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
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
        $validator = $this->validateRequest($request, [
            'product_id' => 'required|numeric',
            'related_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'product_id',
            'related_id'
        ]);

        $lastItem = $this->repository->find([], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        try {
            $model = $this->repository->create($attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);

        try {
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>')], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($id);
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')], 'order:desc');
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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')], 'order:asc');
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

    public function loadProduct(Request $request) {
        $clauses = [WhereClause::queryDiff('id', $request->product_id)];
        $with = ['category'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');
        $arrId = [];

        $related = $this->repository->get([WhereClause::query('product_id', $request->product_id)], $orderBy, [], []);

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('category_id')) {
            array_push($clauses, WhereClause::query('category_id', $request->category_id));
        }

        if(count($related) > 0) {
            foreach($related as $r) {
                array_push($arrId, $r->related_id);
            }
            array_push($clauses, WhereClause::queryNotIn('id', $arrId));
        }
        $data = $this->productRepository->get($clauses, $orderBy, $with, $withCount);
        return $this->success($data);
    }
}
