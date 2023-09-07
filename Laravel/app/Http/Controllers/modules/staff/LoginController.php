<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\StaffRepositoryInterface;
use App\Utils\AuthUtil;
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

        if ($user->status == 0) {
            return $this->errorClient('Tài khoản chưa được kích hoạt');
        }

        if (!Hash::check($password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }

        return $this->success(['token' => $user->remember_token,'username' => $user->username, 'name' => $user->fullname, 'avatar' => $user->avatar]);
    }

    public function password(Request $request) {
        $user = AuthUtil::getInstance()->getModel();
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }
        $password = $this->repository->update($user->id, [
            'password' => Hash::make($request->newPassword)
        ]);
        return $this->success($password);
    }
}
