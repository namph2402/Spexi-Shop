<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Product;
use App\Repository\ImportNoteDetailRepositoryInterface;
use App\Repository\ImportNoteRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductColorRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductSizeRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use App\Utils\AuthUtil;
use App\Utils\FileStorageUtil;
use App\Utils\OfficeUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends RestController
{
    protected $repository;
    protected $tagRepository;
    protected $categoryRepository;
    protected $promotionRepository;
    protected $colorRepository;
    protected $sizeRepository;
    protected $warehouseRepository;
    protected $importRepository;
    protected $importDetailRepository;

    public function __construct(
        ProductRepositoryInterface          $repository,
        ProductCategoryRepositoryInterface  $categoryRepository,
        ProductTagRepositoryInterface       $tagRepository,
        PromotionRepositoryInterface        $promotionRepository,
        ProductSizeRepositoryInterface      $sizeRepository,
        ProductColorRepositoryInterface     $colorRepository,
        WarehouseRepositoryInterface        $warehouseRepository,
        ImportNoteRepositoryInterface       $importRepository,
        ImportNoteDetailRepositoryInterface $importDetailRepository
    ) {
        parent::__construct($repository);
        $this->repository = $repository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
        $this->promotionRepository = $promotionRepository;
        $this->colorRepository = $colorRepository;
        $this->sizeRepository = $sizeRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->importRepository = $importRepository;
        $this->importDetailRepository = $importDetailRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['category', 'article', 'tags', 'warehouses.size', 'warehouses.color', 'warehouseViews.size', 'warehouseViews.color'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([WhereClause::queryLike('code', $request->search), WhereClause::queryLike('name', $request->search)]));
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
        $createdImages = [];

        $validator = $this->validateRequest($request, [
            'category_id' => 'required|numeric',
            'category_slug' => 'required|max:255',
            'name' => 'required|max:255|unique:products',
            'code' => 'required|max:255',
            'image' => 'required|mimes:jpeg,png,jpg,gif',
            'price' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'category_id',
            'category_slug',
            'name',
            'code',
            'summary',
            'price',
            'status'
        ]);

        $image = FileStorageUtil::putFile('product_image', $request->file('image'));
        array_push($createdImages, $image);
        $attributes['image'] = $image;
        $attributes['sale_price'] = $request->price;
        $attributes['slug'] = Str::slug($attributes['name']);

        $lastItem = $this->repository->find([], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
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
        $createdImages = [];

        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $image_old = $model->image;

        $validator = $this->validateRequest($request, [
            'category_id' => 'nullable|numeric',
            'name' => 'nullable|max:255|unique:products,name,' . $id,
            'code' => 'nullable|max:255',
            'price' => 'nullable|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'category_id',
            'category_slug',
            'name',
            'code',
            'summary',
            'price'
        ]);

        if ($request->file('image') != '') {
            $image = FileStorageUtil::putFile('product_image', $request->file('image'));
            array_push($createdImages, $image);
            $attributes['image'] = $image;
        }

        $attributes['sale_price'] = $request->price;
        $attributes['slug'] = Str::slug($attributes['name']);

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
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
        $image = $model->image;

        try {
            DB::beginTransaction();
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>')], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($id, ['article', 'images', 'warehouses', 'cartItem', 'relateds']);
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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')], 'order:desc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }

        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id, [
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id, [
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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')], 'order:asc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }

        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update($id, [
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id, [
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

    public function loadTag(Request $request)
    {
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

        try {
            DB::beginTransaction();
            $this->repository->detach($model, $request->tag_ids);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

    public function loadAvailableProducts($id, Request $request)
    {
        $model = $this->promotionRepository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['promotions'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        foreach ($model->products as $product) {
            if ($product) {
                array_push($clauses, WhereClause::queryDiff('id', $product->id));
            }
        }

        if (isset($request->search)) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if (isset($request->category_id)) {
            array_push($clauses, WhereClause::query('category_id', $request->category_id));
        }

        if (isset($request->status) && $request->status != '') {
            array_push($clauses, WhereClause::query('published', $request->status));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function import(Request $request)
    {
        set_time_limit(0);
        $user = AuthUtil::getInstance()->getModel();
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'file' => 'required',
            'note' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $file = $request->file('file');
        if ($file->getClientOriginalExtension() != '') {
            return $this->errorClient('Không đúng định dạng file .xlsx');
        }

        $newData = OfficeUtil::readXLSX($file->getRealPath(), 0, 2, 'A', -1, 'I');

        if (!empty($newData)) {
            $all = Product::all();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }

            try {
                DB::beginTransaction();
                $total_amount = 0;

                foreach ($newData as $row) {
                    $priceValue = intval($row[3]);
                    $quantityValue = intval($row[8]);
                    $total_amount += $priceValue * $quantityValue;
                }

                $import = $this->importRepository->create([
                    'name' => $request->name,
                    'creator_id' => $user->id,
                    'creator_name' => $user->name,
                    'total_amount' => $total_amount,
                    'description' => $request->note
                ]);

                if ($import) {
                    foreach ($newData as $key => $row) {
                        $categoryValue = trim($row[0]);
                        $codeValue = trim($row[1]);
                        $nameValue = trim($row[2]);
                        $priceValue = intval($row[3]);
                        $codeVariantValue = trim($row[4]);
                        $sizeValue = trim($row[5]);
                        $colorValue = trim($row[6]);
                        $weightValue = doubleval($row[7]);
                        $quantityValue = intval($row[8]);

                        if (empty($categoryValue) || empty($codeValue) || empty($nameValue) || empty($codeVariantValue) || empty($sizeValue) || empty($colorValue)) {
                            Log::error('Lỗi dữ liệu dòng ' . $key + 1);
                            continue;
                        }

                        // Tạo biến thể
                        $size = $this->sizeRepository->find([WhereClause::query('name', $sizeValue)]);
                        if (empty($size)) {
                            $size = $this->sizeRepository->create(['name' => $sizeValue]);
                        }
                        $color = $this->colorRepository->find([WhereClause::query('name', $colorValue)]);
                        if (empty($color)) {
                            $color = $this->colorRepository->create(['name' => $colorValue]);
                        }

                        if (!array_key_exists($codeValue, $dict_products)) {
                            // Tạo danh mục
                            $category = $this->categoryRepository->find([WhereClause::query('name', $categoryValue)]);
                            if (empty($category)) {
                                $orderCategory = 0;
                                $lastItem = $this->categoryRepository->find([], 'order:desc');
                                if (!empty($lastItem)) {
                                    $orderCategory = $lastItem->order + 1;
                                }
                                $category = $this->categoryRepository->create([
                                    'name' => $categoryValue,
                                    'slug' => Str::slug($categoryValue),
                                    'order' => $orderCategory,
                                ]);
                            }

                            // Tạo sản phẩm mới
                            $orderProduct = 0;
                            $lastItem = $this->repository->find([], 'order:desc');
                            if (!empty($lastItem)) {
                                $orderProduct = $lastItem->order + 1;
                            }

                            $product = $this->repository->create([
                                'code' => $codeValue,
                                'category_id' => $category->id,
                                'category_slug' => $category->slug,
                                'slug' => Str::slug($nameValue),
                                'name' => $nameValue,
                                'price' => $priceValue,
                                'sale_price' => $priceValue,
                                'order' => $orderProduct,
                                'image' => '',
                            ]);

                            // Tạo kho
                            $warehouse = $this->warehouseRepository->create([
                                'product_id' => $product->id,
                                'code' => $codeVariantValue,
                                'size_id' => $size->id,
                                'color_id' => $color->id,
                                'weight' => $weightValue,
                                'quantity' => $quantityValue,
                            ]);
                        } else {
                            $product = $dict_products[$codeValue];

                            $this->repository->update($product->id, [
                                'price' => $priceValue,
                                'sale_price' => $priceValue,
                            ]);

                            $warehouse = $this->warehouseRepository->find([WhereClause::query('code', $codeVariantValue)]);

                            if (!empty($warehouse)) {
                                $this->warehouseRepository->update($warehouse->id, [
                                    'weight' => $weightValue,
                                    'quantity' => $warehouse->quantity + $quantityValue
                                ]);
                            } else {
                                // Tạo kho
                                $warehouse = $this->warehouseRepository->create([
                                    'product_id' => $product->id,
                                    'code' => $codeVariantValue,
                                    'size_id' => $size->id,
                                    'color_id' => $color->id,
                                    'weight' => $weightValue,
                                    'quantity' => $quantityValue,
                                ]);
                            }
                        }

                        $this->importDetailRepository->create([
                            'note_id' => $import->id,
                            'name' => $nameValue,
                            'product_id' => $product->id,
                            'product_code' => $product->code,
                            'warehouse_id' => $warehouse->id,
                            'warehouse_code' => $warehouse->code,
                            'price' => $priceValue,
                            'size' => $sizeValue,
                            'color' => $colorValue,
                            'quantity' => $quantityValue,
                            'weight' => $weightValue,
                        ]);
                        DB::commit();
                        $dict_products[$product->code] = $product;
                    }
                }
                return $this->success([]);
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->error($e->getMessage());
            }
            sleep(0.5);
        }
    }

    public function export()
    {
        $xlsx = [
            'Kho hàng' => [['Mã', 'Tên SP', 'Size', 'Màu', 'Giá nhập', 'Giá bán', 'Số lượng', 'Ghi chú']]
        ];

        $data = $this->warehouseRepository->get([], 'code:asc', ['product', 'size', 'color']);

        foreach ($data as $row) {
            array_push($xlsx['Kho hàng'], [
                $row->code,
                $row->product->name,
                $row->size->name,
                $row->color->name,
                $row->product->price,
                $row->product->sale_price,
                $row->quantity,
                null
            ]);
        }

        try {
            $writer = OfficeUtil::writeXLSX($xlsx);
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="ket_qua_' . time() . '.xlsx"');
            $response->headers->set('Cache-Control', 'max-age=0');
            return $response;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
