<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\FormDataRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormDataController extends RestController
{
    public function __construct(FormDataRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'email' => 'required|email',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes['value'] = $request->email;
        $test_email = $this->repository->find([WhereClause::query('value', $request->email)]);
        if ($test_email) {
            return $this->successViewBack('Email của bạn đã được thêm');
        }

        try {
            DB::beginTransaction();
            $this->repository->create($attributes);
            DB::commit();
            return $this->successViewBack('Đăng ký thông tin thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Đăng ký thông tin thất bại');
        }
    }

}
