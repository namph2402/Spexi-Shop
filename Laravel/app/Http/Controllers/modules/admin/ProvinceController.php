<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $data = new Province;

        if(!$request->has('ship')) {
            $data = $data->with('districts.wards');
        }

        $data = $data->get();
        return $this->success($data);
    }
}
