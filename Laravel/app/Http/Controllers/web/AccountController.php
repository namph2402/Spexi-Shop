<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\RetrievalPassword;
use App\Jobs\SendMail;
use App\Models\StoreInformation;
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
        if(Auth::user()) {
            return redirect()->route('home.index');
        }
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
        if(Auth::user()) {
            return redirect()->route('home.index');
        }
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

        if ($checkUser && $checkUser->email_verified_at != null) {
            return redirect()->back()->with('msg_error', 'Tên đăng nhập đã tồn tại')->withInput();
        } else if ($checkEmail && $checkEmail->email_verified_at != null) {
            return redirect()->back()->with('msg_error', 'Email đã đăng ký')->withInput();
        } else {
            if ($checkEmail && $checkEmail->email_verified_at == null) {
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
                return view('pages.capcha', compact('email'));
            } else {
                return $this->error('Không thể tạo tài khoản');
            }
        }
    }

    public function sendCapcha(Request $request)
    {
        if(!$request->has('email')) {
            return redirect('sign-up');
        }

        $name = StoreInformation::whereName('name')->first()->value;
        $email = $request->input('email');

        $checkEmail = $this->repository->find([WhereClause::query('email', $email)]);
        if(!$checkEmail) {
            return redirect('sign-up')->with('msg_error', 'Email chưa được đăng ký');
        }

        $code = rand(1000, 9999);
        $user = $this->repository->update($checkEmail->id,[
            'code' => $code
        ]);
        if ($user) {
            SendMail::dispatch($email, $code, $name);
            return view('pages.capcha', compact('email'));
        }

    }

    public function checkCapcha(Request $request)
    {
        if(!$request->has('email')) {
            return redirect('sign-up');
        }

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
            $msg = "Mã xác nhận không đúng";
            return view('pages.capcha',compact('email', 'msg'));
        }
    }

    public function retrieval()
    {
        if(Auth::user()) {
            return redirect()->route('home.index');
        }
        return view('pages.retrieval');
    }

    public function retrievalPassword(Request $request)
    {
        $name = StoreInformation::whereName('name')->first()->value;
        $user = $this->repository->find([WhereClause::orQuery([WhereClause::query('username', $request->user), WhereClause::query('email', $request->user)])]);

        if(!$user) {
            return $this->errorView('Tài khoản hoặc email không đúng');
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
        if(Auth::user()) {
            return redirect()->route('home.index');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->successView('/', 'Đăng xuất tài khoản thành công');
    }
}
