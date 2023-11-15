<?php

namespace App\Http\Controllers\modules\staff;

use App\Http\Controllers\Controller;
use App\Models\Province;

class ProvinceController extends Controller
{
    public function index()
    {
        $data = Province::with('districts.wards')->get();
        return $this->success($data);
    }
}

