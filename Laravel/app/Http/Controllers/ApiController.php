<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

interface ApiController
{
    public function index(Request $request);

    public function store(Request $request);

    public function show($id);

    public function update(Request $request, $id);

    public function destroy($id);
}
