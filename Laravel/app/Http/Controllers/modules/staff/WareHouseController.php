<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Product;
use App\Repository\ImportNoteDetailRepositoryInterface;
use App\Repository\ImportNoteRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductColorRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductSizeRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use App\Utils\AuthUtil;
use App\Utils\OfficeUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WarehouseController extends RestController
{
    protected $productRepository;
    protected $sizeRepository;
    protected $colorRepository;
    protected $categoryRepository;
    protected $importRepository;
    protected $importDetailRepository;

    public function __construct(
        WarehouseRepositoryInterface       $repository,
        ProductRepositoryInterface         $productRepository,
        ProductSizeRepositoryInterface     $sizeRepository,
        ProductColorRepositoryInterface    $colorRepository,
        ProductCategoryRepositoryInterface $categoryRepository,
        ImportNoteRepositoryInterface       $importRepository,
        ImportNoteDetailRepositoryInterface $importDetailRepository
    )
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->sizeRepository = $sizeRepository;
        $this->colorRepository = $colorRepository;
        $this->categoryRepository = $categoryRepository;
        $this->importRepository = $importRepository;
        $this->importDetailRepository = $importDetailRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['size', 'color', 'product'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('product_id')) {
            array_push($clauses, WhereClause::query('product_id', $request->product_id));
        }

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::orQuery([
                WhereClause::queryLike('code', $request->search),
                WhereClause::queryRelationHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                })
            ]));
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
            'sizeArr' => 'required',
            'colorArr' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'product_id',
        ]);

        $product = $this->productRepository->findById($request->product_id);
        foreach ($request->sizeArr as $s) {
            $size = $this->sizeRepository->findById($s);
            foreach ($request->colorArr as $c) {
                $color = $this->colorRepository->findById($c);
                $attributes['code'] = strtoupper(Str::slug($product->code . '-' . $size->name . '-' . $color->name));
                $attributes['size_id'] = $s;
                $attributes['color_id'] = $c;
                $attributes['weight'] = "0.2";
                $test_name = $this->repository->find([WhereClause::query('product_id', $request->product_id), WhereClause::query('size_id', $s), WhereClause::query('color_id', $c)]);
                if ($test_name) {
                    continue;
                } else {
                    try {
                        DB::beginTransaction();
                        $this->repository->create($attributes);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                    }
                }
            }
        }
        return $this->success([]);
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'weight' => 'nullable|numeric',
            'quantity' => 'nullable|max:255'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['weight'] = $request->input('weight', 0);
        $attributes['quantity'] = $request->input('quantity', 0);

        if($attributes['quantity'] <=0 ) {
            $attributes['status'] = 0;
        }

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
        if($model->quantity <= 0) {
            return $this->errorClient('Sản phẩm đã hết hàng');
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

    public function import(Request $request)
    {
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
        if ($file->getClientOriginalExtension() != 'xlsx') {
            return $this->errorClient('Không đúng định dạng file .xlsx');
        }

        $newData = OfficeUtil::readXLSX($file->getRealPath(), 0, 2, 'A', -1, 'I');

        if (!empty($newData)) {
            $all = Product::all();
            $dict_products = [];
            foreach ($all as $c) {
                $dict_products[$c->code] = $c;
            }
            foreach ($newData as $key => $row) {
                $i = $key + 1;
                $categoryValue = trim($row[0]);
                $codeValue = trim($row[1]);
                $nameValue = trim($row[2]);
                $priceValue = intval($row[3]);
                $codeVariantValue = trim($row[4]);
                $sizeValue = trim($row[5]);
                $colorValue = trim($row[6]);
                $weightValue = trim($row[7]);
                $quantityValue = intval($row[8]);

                if (empty($categoryValue) || empty($codeValue) || empty($nameValue) || empty($codeVariantValue) || empty($sizeValue) || empty($colorValue)) {
                    return $this->errorClient('Lỗi dữ liệu dòng ' . $i);
                }
            }
            try {
                DB::beginTransaction();
                $import = $this->importRepository->create([
                    'name' => $request->name,
                    'creator_id' => $user->id,
                    'creator_name' => $user->fullname,
                    'description' => $request->note
                ]);

                if($import) {
                    foreach ($newData as $row) {
                        $categoryValue = trim($row[0]);
                        $codeValue = trim($row[1]);
                        $nameValue = trim($row[2]);
                        $priceValue = intval($row[3]);
                        $codeVariantValue = trim($row[4]);
                        $sizeValue = trim($row[5]);
                        $colorValue = trim($row[6]);
                        $weightValue = doubleval($row[7]);
                        $quantityValue = intval($row[8]);

                        $size = $this->sizeRepository->find([WhereClause::query('name', $sizeValue)]);
                        if (empty($size)) {
                            $size = $this->sizeRepository->create(['name' => $sizeValue]);
                        }
                        $color = $this->colorRepository->find([WhereClause::query('name', $colorValue)]);
                        if (empty($color)) {
                            $color = $this->colorRepository->create(['name' => $colorValue]);
                        }

                        if (!array_key_exists($codeValue, $dict_products)) {
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

                            $orderProduct = 0;
                            $lastItem = $this->productRepository->find([], 'order:desc');
                            if (!empty($lastItem)) {
                                $orderProduct = $lastItem->order + 1;
                            }

                            $product = $this->productRepository->create([
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

                            $warehouse = $this->repository->create([
                                'product_id' => $product->id,
                                'code' => $codeVariantValue,
                                'size_id' => $size->id,
                                'color_id' => $color->id,
                                'weight' => $weightValue,
                                'quantity' => $quantityValue,
                            ]);
                        } else {
                            $product = $dict_products[$codeValue];

                            $this->productRepository->update($product->id, [
                                'price' => $priceValue,
                                'sale_price' => $priceValue,
                            ]);

                            $warehouse = $this->repository->find([WhereClause::query('code', $codeVariantValue)]);

                            if (!empty($warehouse)) {
                                $this->repository->update($warehouse->id, [
                                    'weight' => $weightValue,
                                    'quantity' => $warehouse->quantity + $quantityValue
                                ]);
                            } else {
                                $warehouse = $this->repository->create([
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

        $data = $this->repository->get([],'code:asc',['product','size','color']);

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
