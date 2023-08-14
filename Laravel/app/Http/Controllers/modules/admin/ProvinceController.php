<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\Province;

class ProvinceController extends Controller
{
    public function index()
    {
        $data = Province::with('districts.wards')->orderBy('name', 'asc')->get();
        return $this->success($data);
    }

}
