<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\SystemUserRepositoryInterface;
use App\Utils\AuthUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $user = AuthUtil::getInstance()->getModel();
        
        if (!Hash::check($request->password, $user->password)) {
            return $this->errorClient('Mật khẩu không đúng');
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($user->id, [
                'password' => Hash::make($request->newPassword)
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function repassword()
    {
        $user = AuthUtil::getInstance()->getModel();

        try {
            DB::beginTransaction();
            $model = $this->repository->update($user->id,[
                'password' => Hash::make('123456a@')
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
