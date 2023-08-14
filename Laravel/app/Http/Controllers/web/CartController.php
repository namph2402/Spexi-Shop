<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\CartItemRepositoryInterface;
use App\Repository\CartRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends RestController
{
    protected $productRepository;
    protected $itemRepository;
    protected $warehouseRepository;

    public function __construct(
        CartRepositoryInterface $repository,
        CartItemRepositoryInterface $itemRepository,
        ProductRepositoryInterface $productRepository,
        WarehouseRepositoryInterface $warehouseRepository
    ) {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->itemRepository = $itemRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(Request $request)
    {
        $cart = $this->repository->find([WhereClause::query('user_id', Auth::user()->id)], null, ['items.product', 'items.warehouse.sizes', 'items.warehouse.colors']);
        return view('orders.cart', compact('cart'));
    }

    public function addItem(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'product_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'color_id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'product_id',
            'quantity'
        ]);

        $warehouse = $this->warehouseRepository->find([WhereClause::query('size_id', $request->size_id), WhereClause::query('color_id', $request->color_id)]);
        $product = $this->productRepository->findByID($request->product_id);
        $cart = $this->repository->find([WhereClause::query('user_id', Auth::user()->id)]);

        $attributes['cart_id'] = $cart->id;
        $attributes['warehouse_id'] = $warehouse->id;
        $attributes['amount'] = $product->sale_price * $request->quantity;

        $clause = [
            WhereClause::query('cart_id', $cart->id),
            WhereClause::query('product_id', $request->product_id),
            WhereClause::query('warehouse_id', $warehouse->id),
        ];

        $test_item = $this->itemRepository->find($clause);

        try {
            DB::beginTransaction();
            if ($test_item) {
                $attributes['quantity'] = $request->quantity + $test_item->quantity;
                $attributes['amount'] = $attributes['quantity'] * $product->sale_price;
                $this->itemRepository->update($test_item->id, $attributes);
            } else {
                $this->itemRepository->create($attributes);
            }
            DB::commit();
            return $this->successView('/cart','Thêm sản phẩm vào giỏ hàng thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Thêm sản phẩm vào giỏ hàng thất bại');
        }
    }


    public function updateItem(Request $request, $id)
    {
        $quantity = [];
        $totalAmount = 0;

        $item =  $this->itemRepository->findById($id, ['product']);
        if (empty($item)) {
            return $this->errorNotFoundView();
        }

        if ($request->quantity > 0) {
            $data = $this->itemRepository->update($id, [
                'quantity' => $request->quantity,
                'amount' => $request->quantity * $item->product->sale_price,
            ]);

            $cart = $this->repository->find(WhereClause::query('cart_id', $item->cart_id), null, ['items']);
            foreach ($cart->items as $i) {
                $totalAmount += $i->amount;
            }

            $quantity = [
                'id' =>  $data->id,
                'quantity' =>  $data->quantity,
                'amount' =>  $data->amount,
                'totalAmount' => $totalAmount,
            ];
        } else {
            $this->itemRepository->delete($id);
        }
        return $this->success($quantity);
    }

    public function deleteItem($id)
    {
        $model = $this->itemRepository->findById($id);
        if (empty($model)) {
            return $this->errorNotFoundView();
        }
        try {
            DB::beginTransaction();
            $this->itemRepository->delete($id);
            DB::commit();
            return redirect()->back()->with('msg_success', 'Xóa sản phẩm khỏi giỏ hàng thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->with('msg_error', 'Xóa sản phẩm khỏi giỏ hàng thất bại');
        }
    }
}
