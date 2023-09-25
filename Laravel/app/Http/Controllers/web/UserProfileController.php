<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends RestController
{
    protected $orderRepository;
    protected $userRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;

    public function __construct(
        UserProfileRepositoryInterface $repository,
        OrderRepositoryInterface       $orderRepository,
        UserRepositoryInterface        $userRepository,
        ProvinceRepositoryInterface    $provinceRepository,
        DistrictRepositoryInterface    $districtRepository,
        WardRepositoryInterface        $wardRepository
    )
    {
        parent::__construct($repository);
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
    }

    public function index(Request $request)
    {
        $provinceUser = null;
        $districtUser = null;
        $wardUser = null;
        $clauses = [WhereClause::query('user_id', Auth::user()->id)];

        $profile = $this->repository->find($clauses, null, ['account']);

        if($profile->province != null) {
            $provinceUser = $this->provinceRepository->find([WhereClause::query('name', $profile->province)], 'id:asc', ['districts']);
        }

        if($profile->district != null) {
            $districtUser = $this->districtRepository->find([WhereClause::query('name', $profile->district)], 'id:asc', ['wards']);
        }

        if($profile->ward != null) {
            $wardUser = $this->wardRepository->find([WhereClause::query('name', $profile->ward)], 'id:asc');
        }

        $provinces = $this->provinceRepository->get([], 'id:asc');
        return view('profile.user', compact('profile','provinces','provinceUser', 'districtUser', 'wardUser'));
    }

    public function store(Request $request)
    {
        $createdImages = [];
        $validator = $this->validateRequest($request, [
            'fullname' => 'required|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|max:255',
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'ward_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorView($validator);
        }

        $attributes = $request->only([
            'fullname',
            'gender',
            'dob',
            'phone',
            'address'
        ]);

        $province = $this->provinceRepository->findById($request->province_id);
        $district = $this->districtRepository->findById($request->district_id);
        $ward = $this->wardRepository->findById($request->ward_id);

        if(!$province || !$district || !$ward) {
            return $this->errorView('Địa chỉ không đúng');
        }

        $attributes['province'] = $province->name;
        $attributes['district'] = $district->name;
        $attributes['ward'] = $ward->name;

        if ($request->file != null) {
            $image = FileStorageUtil::putFile('avatar', $request->file('file'));
            $attributes['avatar'] = $image;
            array_push($createdImages, $image);
        }

        try {
            DB::beginTransaction();
            $this->repository->bulkUpdate([WhereClause::query('user_id', Auth::user()->id)], $attributes);
            DB::commit();
            return $this->successView('/profile','Cập nhật thông tin thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Cập nhật thông tin thất bại');
        }
    }

    public function order(Request $request)
    {
        $clauses = [WhereClause::query('user_id', Auth::user()->id)];
        $orders = $this->orderRepository->paginate(10, $clauses, null, ['details.product']);
        return view('profile.order', compact('orders'));
    }

    public function orderDetail(Request $request, $id)
    {
        $order = $this->orderRepository->findById($id, ['user', 'details.product']);
        if (empty($order) || $order->user_id != Auth::user()->id) {
            return $this->errorNotFoundView();
        }

        return view('profile.order-detail', compact('order'));
    }

    public function password(Request $request)
    {
        $user = $this->userRepository->findById(Auth::user()->id);
        return view('profile.password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'oldPassword' => 'required|min:6',
            'password' => 'required|min:6'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $user = $this->userRepository->findById(Auth::user()->id);
        if (!Hash::check($request->oldPassword, $user->password)) {
            return redirect()->back()->with('msg_error','Mật khẩu không đúng');
        }

        $user = $this->userRepository->update(Auth::user()->id, [
            'password' =>  Hash::make($request->password)
        ]);

        return $this->successView('profile/password','Đã đổi mật kẩu thành công');
    }
}
