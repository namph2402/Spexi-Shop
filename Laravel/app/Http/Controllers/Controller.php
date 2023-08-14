<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data, $message = 'Successfully', $status = 1)
    {
        return response()->json(['data' => $data, 'message' => $message, 'status' => $status], 200);
    }

    public function errorClient($message = 'Bad request', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 400);
    }

    public function errorNotFound($message = 'ID không tồn tại', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 400);
    }

    public function error($message = 'System error', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 500);
    }

    public function notSupport($message = 'Method not support', $payload = [])
    {
        return response()->json(['message' => $message, 'payload' => $payload, 'status' => 0], 405);
    }

}