<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\StaffRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends RestController
{

    public function __construct(StaffRepositoryInterface $repository)
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

        if ($user->status == 0) {
            return $this->errorClient('Tài khoản bị khóa');
        }

        return $this->success(['token' => $user->remember_token, 'name' => $user->fullname, 'avatar' => $user->avatar]);
    }
}
