<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\BannerRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BannerController extends RestController
{
    public function __construct(BannerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $data = $this->repository->get([WhereClause::query('group_id', $request->group_id)], 'order:asc');
        return $data;
    }

    public function store(Request $request)
    {
        $createdImages = [];

        $validator = Validator::make($request->all(), [
            'group_id' => 'required|numeric',
            'name' => 'required|max:255',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'group_id',
            'name',
            'href',
            'alt',
            'summary'
        ]);

        $image = FileStorageUtil::putFile('banners', $request->file('image'));
        $attributes['image'] = $image;
        array_push($createdImages, $image);

        $lastItem = $this->repository->find([WhereClause::query('group_id', $request->group_id)], 'order:desc');
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
            FileStorageUtil::deleteFiles($createdImages);
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
            'name' => 'nullable|max:255',
            'image' => 'nullable',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'href',
            'alt',
            'summary'
        ]);

        if ($request->file('image') != '') {
            $image = FileStorageUtil::putFile('banners', $request->file('image'));
            $attributes['image'] = $image;
            array_push($createdImages, $image);
        }

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
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>'), WhereClause::query('group_id', $model->group_id)], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($model);
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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<'), WhereClause::query('group_id', $model->group_id)], 'order:desc');
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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>'), WhereClause::query('group_id', $model->group_id)], 'order:asc');
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
