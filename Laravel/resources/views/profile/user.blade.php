@extends('profile.profile')
@section('title')
    Thông tin tài khoản
@endsection
@section('content-child')
    <form action="/profile/update-profile" method="post" enctype="multipart/form-data" class="col-lg-9" name="updateProfile"
        id="updateProfile">
        <div class="row">
            <div class="col-12 p-0">
                <nav class="breadcrumb bg-light mb-3">
                    <a class="breadcrumb-item text-dark" href="/">Trang chủ</a>
                    <span class="breadcrumb-item active">Thông tin người dùng</span>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8" style="background: white">
                <div class="p-2 pt-3">
                    <div class="d-flex">
                        <div class="col-md-6 form-group">
                            <label class="pb-1">Tên đăng nhập</label>
                            <input class="form-control" type="text" disabled value="{{ $profile->account->username }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="pb-1">Email đăng ký</label>
                            <div class="d-flex align-items-center">
                                <input class="form-control" type="email" name="email" value="{{ $profile->account->email }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="col-md-6 form-group">
                            <label class="pb-1">Tên khách hàng</label>
                            <input class="form-control" type="text" name="fullname" placeholder="Nhập tên" value="{{ $profile->fullname }}">
                            <small class="err-mess-user d-none" id="errName">Vui lòng nhập đúng thông tin</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="pb-1">Số điện thoại</label>
                            <input class="form-control" type="number" name="phone" placeholder="Nhập số điện thoại" value="{{ $profile->phone }}">
                            <small class="err-mess-user d-none" id="errPhone">Vui lòng nhập đúng thông tin</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pb-1" style="padding-left: 15px">Địa chỉ</label>
                        <div class="w-100 d-flex justify-content-between">
                            <div class="col-md-4">
                                <select class="custom-select" name="province_id" id="province_id"
                                    onchange="onProvinceIdChange()">
                                    @if ($provinceUser != null)
                                        <option selected hidden value="{{ $provinceUser->id }}">{{ $provinceUser->name }}
                                        </option>
                                    @else
                                        <option selected hidden disabled value="">Tỉnh/Thành phố</option>
                                    @endif
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="custom-select" name="district_id" id="district_id" onchange="onDistrictIdChange()">
                                    @if ($districtUser != null)
                                        <option selected hidden value="{{ $districtUser->id }}">{{ $districtUser->name }}</option>
                                    @else
                                        <option selected hidden disabled value="">Quận/huyện</option>
                                    @endif
                                    @if ($provinceUser != null)
                                        @foreach ($provinceUser->districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="custom-select" name="ward_id" id="ward_id">
                                    @if ($wardUser != null)
                                        <option selected hidden value="{{ $wardUser->id }}">{{ $wardUser->name }}</option>
                                    @else
                                        <option selected hidden disabled value="">Xã/phường</option>
                                    @endif
                                    @if ($districtUser != null)
                                        @foreach ($districtUser->wards as $ward)
                                            <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <small class="err-mess-user d-none" id="errAddress">Vui lòng nhập đúng thông tin</small>
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="pb-1">Địa chỉ chi tiết</label>
                        <input class="form-control" type="text" name="address" placeholder="Địa chỉ" value="{{ $profile->address }}">
                        <small class="err-mess-user d-none" id="errAdd">Vui lòng nhập đúng thông tin</small>
                    </div>
                    <div class="col-md-12 form-group">
                        <div class="w-100 d-flex align-items-center">
                            <label class="pb-1 mr-5">Giới tính</label>
                            <div class="col-lg-6 d-flex justify-content-between">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="gender" class="custom-control-input" id="1"
                                        value="1" @if ($profile->gender == 1) checked @endif>
                                    <label class="custom-control-label mt-0" for="1">Nam</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="gender" class="custom-control-input" id="2"
                                        value="2" @if ($profile->gender == 2) checked @endif>
                                    <label class="custom-control-label mt-0" for="2">Nữ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group mt-2">
                        <label class="pb-1">Ngày sinh</label>
                        <input class="form-control" type="date" name="dob" value="{{ $profile->dob }}">
                    </div>
                    <div class="mt-5" style="text-align:center">
                        <button type="submit" class="btn btn-primary mb-3" id="btnUpdate">Cập nhật</button>
                        <a href="/profile/password" class="btn btn-primary mb-3 ml-3">Đổi mật khẩu</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 p-0" style="background: white">
                <div class="mt-5" style="border-left:1px solid rgb(237, 237, 237); height:500px">
                    <div class="d-flex justify-content-center pt-5">
                        <img src="{{ $profile->avatar }}"
                            style="border-radius: 50%; width:200px; height:200px; border: 1px solid #c9c9c9"
                            id="img-preview">
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <input type="file" name="file" id="file" class="inputfile" />
                        <label class="p-1 pr-3 pl-3" for="file" style="color: #3D464D">Tải ảnh</label>
                    </div>
                </div>
            </div>
        </div>
        @csrf
    </form>

    <script>
        const input = document.getElementById('file');
        const image = document.getElementById('img-preview');

        input.addEventListener('change', (e) => {
            if (e.target.files.length) {
                const src = URL.createObjectURL(e.target.files[0]);
                image.src = src;
            }
        });
    </script>
@endsection
