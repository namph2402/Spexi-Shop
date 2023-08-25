<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use App\Utils\AuthUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthenticateStaff
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!$request->hasHeader('Authorization')) {
            return response('token not provided', 401);
        }

        $token = $request->header('Authorization');

        if (Str::startsWith("Bearer ", $token)) {
            return response()->json('token invalid', 401);
        }

        $token = Str::replaceFirst('Bearer ', '', $token);

        $user = Staff::where('remember_token', $token)->first();

        if (empty($user)) {
            return response('token invalid', 401);
        }

        AuthUtil::getInstance()->setModel($user);
        return $next($request);
    }
}
