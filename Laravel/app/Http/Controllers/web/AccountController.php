<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\RetrievalPassword;
use App\Jobs\SendMail;
use App\Models\StoreInformation;
use App\Models\User;
use App\Repository\CartRepositoryInterface;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends RestController
{
    protected $cartRepository;
    protected $profileRepository;

    public function __construct(
        UserRepositoryInterface $repository,
        CartRepositoryInterface $cartRepository,
        UserProfileRepositoryInterface $profileRepository
    ) {
        parent::__construct($repository);
        $this->cartRepository = $cartRepository;
        $this->profileRepository = $profileRepository;
    }

    public function index(Request $request)
    {
        return view('pages.login');
    }

    public function checkLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->repository->find([WhereClause::orQuery([WhereClause::query('username', $username), WhereClause::query('email', $username)])]);

        if($user) {
            if (Auth::attempt(['username' => $user->username, 'password' => $password])) {
                if (Auth::user()->status == 1) {
                    $request->session()->regenerate();
                    return $this->successView('/', 'Bạn đã đăng nhập thành công');
                } else {
                    return $this->errorView('Tài khoản bị khóa hoặc chưa được kích hoạt');
                }
            }
            else {
                return $this->errorView('Mật khẩu không đúng');
            }
        } else {
            return $this->errorView('Tài khoản không đúng');
        }
    }

    public function signup()
    {
        return view('pages.signup');
    }

    public function checkSignup(Request $request)
    {
        $name = StoreInformation::whereName('name')->first()->value;
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');

        $checkUser = $this->repository->find([WhereClause::query('username', $username)]);
        $checkEmail = $this->repository->find([WhereClause::query('email', $email)]);

        if ($checkUser != null) {
            return redirect()->back()->with('msg_error', 'Tên đăng nhập đã tồn tại')->withInput();
        } else if ($checkEmail != null && $checkEmail->email_verified_at != null) {
            return redirect()->back()->with('msg_error', 'Email đã đăng ký')->withInput();
        } else {
            if ($checkEmail != null && $checkEmail->email_verified_at == null) {
                $this->repository->delete($checkEmail->id);
            }
            $attributes = $request->only([
                'username',
                'email',
            ]);
            $attributes['password'] = Hash::make($password);
            $attributes['remember_token'] = Str::random(100);
            $code = rand(1000, 9999);
            $attributes['code'] = $code;
            $user = $this->repository->create($attributes);
            if ($user) {
                SendMail::dispatch($email, $code, $name);
                return redirect('check-capcha')->with('email', $email);
            }
        }
    }

    public function viewCapcha(Request $request)
    {
        $email = $request->email;
        return view('pages.capcha', compact('email'));
    }

    public function checkCapcha(Request $request)
    {
        $date = Date("Y-m-d");
        $email = $request->email;
        $user = $this->repository->find([WhereClause::query('email', $email)]);
        if (!$user) {
            return redirect('sign-up');
        }
        if ($user->code == $request->code) {
            $userUpdate = $this->repository->update($user->id, [
                'status' => 1,
                'email_verified_at' => $date
            ]);
            if ($userUpdate) {
                $this->cartRepository->create(['user_id' => $user->id]);
                $this->profileRepository->create(['user_id' => $user->id]);
            }
            return $this->successView('/sign-in', 'Đã tạo tài khoản thành công');
        } else {
            return redirect()->back()->with('msg_error', 'Mã xác nhận không đúng')->with('email', $email)->withInput();
        }
    }

    public function retrieval()
    {
        return view('pages.retrieval');
    }

    public function retrievalPassword(Request $request)
    {
        $name = StoreInformation::whereName('name')->first()->value;
        $user = User::where("username", "=", $request->user)->orWhere("email", "=", $request->user)->first();
        if(!$user) {
            return $this->error('Tài khoản hoặc email không đúng');
        } else {
            $password = Str::random(6);
            $passwordNew = $this->repository->update($user->id,[
                "password" => Hash::make($password)
            ]);
            if($passwordNew) {
                RetrievalPassword::dispatch($name, $user->email,$user->username, $password);
                return $this->successView('/sign-in', 'Mật khẩu đã được gửi đến email '.$user->email);
            } else {
                return $this->errorView('Không thể lấy lại được mật khẩu');
            }
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->successView('/', 'Đăng xuất tài khoản thành công');
    }
}
