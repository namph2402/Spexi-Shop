<?php

namespace App\Http\Controllers\modules\staff;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $data = District::whereProvinceId($request->province_id)->orderBy('name', 'asc')->get();
        return $this->success($data);
    }
}
