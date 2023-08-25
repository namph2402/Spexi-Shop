<?php

namespace App\Http\Controllers\modules\staff;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index(Request $request)
    {
        $data = Ward::whereDistrictId($request->district_id)->orderBy('name', 'asc')->get();
        return $this->success($data);
    }
}
