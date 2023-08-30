<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\SystemUserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends RestController
{

    public function __construct(SystemUserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->repository->find([
            WhereClause::query('username', $username)
        ]);

        if (empty($user)) {
            return $this->errorClient('Tài khoản không đúng');
        }

        if (!Hash::check($password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }

        return $this->success(['token' => $user->remember_token,'username' => $user->username, 'name' => $user->name, 'avatar' => $user->avatar]);
    }

    public function password(Request $request) {
        $user = $this->repository->find([WhereClause::query('name', 'admin')]);
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }
        $password = $this->repository->update($user->id, [
            'password' => Hash::make($request->newPassword),
            'remember_token' => Str::random(100),
        ]);
        return $this->success($password);
    }
}
