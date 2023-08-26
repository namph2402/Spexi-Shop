(function ($) {
    "use strict";

    $(document).on("keypress", "form", function (event) {
        return event.keyCode != 13;
    });

    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }

        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });

    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });

    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 2
            },
            576: {
                items: 3
            },
            768: {
                items: 4
            },
            992: {
                items: 5
            },
            1200: {
                items: 6
            }
        }
    });

    $('.related-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 4
            }
        }
    });

    $('.quantity a').on('click', function () {
        var a = $(this);
        var oldValue = a.parent().parent().find('input').val();
        var cartId = a.parent().parent().parent().parent().find('input').val();

        if (a.hasClass('btn-plus')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        //Update giỏ hàng
        Checkout.getInstance().updateQuantity(cartId, newVal, (data) => {
            if (data.length == 0) {
                location.reload();
            } else {
                const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
                const amount = VND.format(data.amount);
                const totalAmount = VND.format(data.totalAmount);
                const name = 'amountItem' + `${cartId}`;
                document.getElementById(name).innerHTML = `${amount} đ`;
                document.getElementById("totalAmount").innerHTML = `${totalAmount} đ`;
            }
        })

        a.parent().parent().find('input').val(newVal);
    });

    $('#formSearch').on('submit', function (e) {
        var v = $('input[name=search]', '#formSearch').val();
        if (v.trim().length == 0) {
            e.preventDefault();
        }
    });

    $('#formSearchPost').on('submit', function (e) {
        var v = $('input[name=search]', '#formSearchPost').val();
        if (v.trim().length == 0) {
            e.preventDefault();
        }
    });

    $('#formEmail').on('submit', function (e) {
        var v = $('input[name=email]', '#formEmail').val();
        if (v.trim().length == 0) {
            e.preventDefault();
        }
    });

    $('#formComment').on('submit', function (e) {
        var v = $('input[name=rating]', '#formComment').val();
        var p = $('input[name=content]', '#formComment').val();
        if (v.trim().length == 0 || p.trim().length == 0) {
            e.preventDefault();
        }
    });

    $('#updateProfile').on('submit', function (e) {
        var name = document.updateProfile.fullname.value;
        var phone = document.updateProfile.phone.value;
        var ward = document.updateProfile.ward_id.value;
        var address = document.updateProfile.address.value;

        const regexName = new RegExp('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$');
        const regexPhone = new RegExp('^(0)[0-9]{9}$');

        if (name == "" || regexName.test(name) == false) {
            $('#errName').toggleClass("view-err");
            document.getElementById("btnUpdate").disabled = true
            e.preventDefault();
            setTimeout(() => [$('#errName').toggleClass("view-err"), document.getElementById("btnUpdate").disabled = false], 2000);
        }
        if (phone == "" || regexPhone.test(phone) == false) {
            $('#errPhone').toggleClass("view-err");
            document.getElementById("btnUpdate").disabled = true
            e.preventDefault();
            setTimeout(() => [$('#errPhone').toggleClass("view-err"), document.getElementById("btnUpdate").disabled = false], 2000);
        }
        if (ward == "") {
            $('#errAddress').toggleClass("view-err");
            document.getElementById("btnUpdate").disabled = true
            e.preventDefault();
            setTimeout(() => [$('#errAddress').toggleClass("view-err"), document.getElementById("btnUpdate").disabled = false], 2000);
        }
        if (address == "") {
            $('#errAdd').toggleClass("view-err");
            document.getElementById("btnUpdate").disabled = true
            e.preventDefault();
            setTimeout(() => [$('#errAdd').toggleClass("view-err"), document.getElementById("btnUpdate").disabled = false], 2000);
        }
    });

    $('#login').on('submit', function (e) {
        var u = $('input[name=username]', '#login').val();
        var p = $('input[name=password]', '#login').val();
        if (u.trim().length == 0 || p.trim().length == 0) {
            e.preventDefault();
        }
    });

    $('#capcha').on('submit', function (e) {
        var u = $('input[name=code]', '#capcha').val();
        if (u.trim().length == 0) {
            e.preventDefault();
        }
    });

    $('#retrieval').on('submit', function (e) {
        var u = $('input[name=user]', '#retrieval').val();
        if (u.trim().length == 0) {
            e.preventDefault();
        }
    });

    $(document).ready(function () {
        $('#formDetail').on('submit', function (e) {
            var s = $('input[name=size_id]:checked', '#formDetail').val();
            var c = $('input[name=color_id]:checked', '#formDetail').val();
            if (s == undefined || c == undefined) {
                $('#errText').toggleClass("view-err");
                e.preventDefault();
            }
        })
    });

    $(document).ready(function () {
        $('#checkout').on('submit', function (e) {
            var arrItem = [];
            var checkbox = document.getElementsByClassName('cart-item');
            for (var i = 0; i < checkbox.length; i++) {
                if (checkbox[i].checked === true) {
                    arrItem.push(checkbox[i].value);
                }
            }
            if (arrItem.length == 0) {
                $('#errText').toggleClass("view-err");
                e.preventDefault();
            } else {
                document.getElementById('item').value = arrItem;
            }
        })
    });

    $(document).ready(function () {
        $('#formSearchP').on('submit', function (e) {
            var arrItemColor = [];
            var arrItemSize = [];
            var colorBox = $('.colorItem:checkbox:checked');
            var sizeBox = $('.sizeItem:checkbox:checked');
            for (var i = 0; i < colorBox.length; i++) {
                if (colorBox[i].checked === true) {
                    arrItemColor.push(colorBox[i].value);
                }
            }
            for (var i = 0; i < sizeBox.length; i++) {
                if (sizeBox[i].checked === true) {
                    arrItemSize.push(sizeBox[i].value);
                }
            }
            if (arrItemColor.length > 0) {
                document.getElementById('color').value = arrItemColor;
            }
            if (arrItemSize.length > 0) {
                document.getElementById('size').value = arrItemSize;
            }
        })
    });

    $(document).ready(function () {
        $('#formCheckout').on('submit', function (e) {
            var name = document.formCheckout.customer_name.value;
            var phone = document.formCheckout.customer_phone.value;
            var province = document.formCheckout.province_id.value;
            var district = document.formCheckout.district_id.value;
            var ward = document.formCheckout.ward_id.value;
            var address = document.formCheckout.customer_address.value;

            const regexName = new RegExp('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$');
            const regexPhone = new RegExp('^(0)[0-9]{9}$');

            if (name == "" || regexName.test(name) == false) {
                $('#errName').toggleClass("view-err");
                document.getElementById("btnOrder").disabled = true
                e.preventDefault();
                setTimeout(() => [$('#errName').toggleClass("view-err"), document.getElementById("btnOrder").disabled = false], 2000);
            }
            if (phone == "" || regexPhone.test(phone) == false) {
                $('#errPhone').toggleClass("view-err");
                document.getElementById("btnOrder").disabled = true
                e.preventDefault();
                setTimeout(() => [$('#errPhone').toggleClass("view-err"), document.getElementById("btnOrder").disabled = false], 2000);
            }
            if (province == "") {
                $('#errProvince').toggleClass("view-err");
                document.getElementById("btnOrder").disabled = true
                e.preventDefault();
                setTimeout(() => [$('#errProvince').toggleClass("view-err"), document.getElementById("btnOrder").disabled = false], 2000);
            }
            if (district == "") {
                $('#errDistrict').toggleClass("view-err");
                document.getElementById("btnOrder").disabled = true
                e.preventDefault();
                setTimeout(() => [$('#errDistrict').toggleClass("view-err"), document.getElementById("btnOrder").disabled = false], 2000);
            }
            if (ward == "") {
                $('#errWard').toggleClass("view-err");
                document.getElementById("btnOrder").disabled = true
                e.preventDefault();
                setTimeout(() => [$('#errWard').toggleClass("view-err"), document.getElementById("btnOrder").disabled = false], 2000);
            }
            if (address == "") {
                $('#errAddress').toggleClass("view-err");
                document.getElementById("btnOrder").disabled = true
                e.preventDefault();
                setTimeout(() => [$('#errAddress').toggleClass("view-err"), document.getElementById("btnOrder").disabled = false], 2000);
            }
        })
    });

    $(document).ready(function () {
        $('#eye').click(function () {
            $(this).toggleClass('open');
            $(this).children('i').toggleClass('fa-eye-slash fa-eye');
            if ($(this).hasClass('open')) {
                $(this).prev().attr('type', 'text');
            } else {
                $(this).prev().attr('type', 'password');
            }
        });

        $('#eye-re').click(function () {
            $(this).toggleClass('open');
            $(this).children('i').toggleClass('fa-eye-slash fa-eye');
            if ($(this).hasClass('open')) {
                $(this).prev().attr('type', 'text');
            } else {
                $(this).prev().attr('type', 'password');
            }
        });
    });

    $(document).ready(function () {
        $('#signup').on('submit', function (e) {
            var username = document.signup.username.value;
            var email = document.signup.email.value;
            var password = document.signup.password.value;
            var re_password = document.signup.re_password.value;

            const regexName = new RegExp('^(?=.*[a-zA-Z0-9]+)[a-zA-Z0-9]*$');
            const regexEmail = new RegExp('[A-Za-z0-9._%-]+@[A-Za-z0-9._%-]+\\.[a-z]{2,3}');
            if (username == "" || regexName.test(username) == false) {
                document.getElementById("errMsg").innerHTML = `Tên đăng nhập chỉ chứa chữ cái, số`;
                e.preventDefault();
            } else {
                if (email == "" || regexEmail.test(email) == false) {
                    document.getElementById("errMsg").innerHTML = `Email không đúng`;
                    e.preventDefault();
                } else {
                    if (password.trim().length < 6) {
                        document.getElementById("errMsg").innerHTML = `Mật khẩu phải lớn hơn 6 kí tự`;
                        e.preventDefault();
                    } else {
                        if (password != re_password) {
                            document.getElementById("errMsg").innerHTML = `Mật khẩu không khớp`;
                            e.preventDefault();
                        }
                    }
                }
            }
        })
    });

    $(document).ready(function () {
        $('#updatePassword').on('submit', function (e) {
            var oldPassword = document.updatePassword.oldPassword.value;
            var password = document.updatePassword.password.value;
            var re_password = document.updatePassword.re_password.value;

            if (oldPassword.trim().length == 0) {
                e.preventDefault();
            } else {
                if (password.trim().length < 6) {
                    document.getElementById("errMsg").innerHTML = `Mật khẩu phải lớn hơn 6 kí tự`;
                    e.preventDefault();
                } else {
                    if (password != re_password) {
                        document.getElementById("errMsg").innerHTML = `Mật khẩu không khớp`;
                        e.preventDefault();
                    }
                }
            }
        })
    });

})(jQuery);

const urlParams = new URLSearchParams(window.location.search);

function setParamsPage(name, value) {
    urlParams.set(name, value);
    window.location.search = urlParams
}

function onProvinceIdChange() {
    const provinceId = document.getElementById('province_id').value;
    Checkout.getInstance().loadAllDistricts(provinceId, (data) => {
        document.getElementById("district_id").innerHTML = "<option selected hidden disabled value=\"\">Quận/huyện</option>";
        document.getElementById("ward_id").innerHTML = `<option selected hidden disabled value="">Xã/phường</option>`;
        for (let i = 0; i < data.length; i++) {
            document.getElementById("district_id").innerHTML += `<option value="${data[i]['id']}">${data[i]['name']}</option>`
        }
    })
}

function onDistrictIdChange() {
    const districtId = document.getElementById('district_id').value;
    Checkout.getInstance().loadAllWards(districtId, (data) => {
        document.getElementById("ward_id").innerHTML = `<option selected hidden disabled value="">Xã/phường</option>`;
        for (let i = 0; i < data.length; i++) {
            document.getElementById("ward_id").innerHTML += `<option value="${data[i]['id']}">${data[i]['name']}</option>`
        }
    })
}

function getFee(status) {
    const provinceId = document.getElementById('province_id').value;
    const districtId = document.getElementById('district_id').value;
    const wardId = document.getElementById('ward_id').value;
    const promotion = document.getElementById('promotion').value;
    if (status == 0 && promotion != 3) {
        Checkout.getInstance().getShipFee(provinceId, districtId, wardId, (data) => {
            const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
            const shipping_fee = VND.format(data.fee);
            document.getElementById("shippingFeeView").innerHTML = `${shipping_fee} đ`;
            document.getElementById("shipping_fee").value = `${data['fee']}`;
        })
    } else {
        const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
        const shipping_fee = VND.format('0');
        document.getElementById("shippingFeeView").innerHTML = `${shipping_fee} đ`;
        document.getElementById("shipping_fee").value = `0`;
    }
    this.getTotal();
}

function applyVoucher() {
    const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });

    const amount = +document.getElementById('amount').value;
    const discount = +document.getElementById('discount').value;
    const code = document.getElementById('voucher').value;
    const dataShip = +document.getElementById("dataShip").value;
    const dataDiscount = +document.getElementById("dataDiscount").value;

    if (code.trim().length > 0) {
        Checkout.getInstance().getVoucher(code, (data) => {
            if (data.length == 0) {
                const shipView = VND.format(dataShip);
                const discountView = VND.format(dataDiscount);
                document.getElementById("amountDiscount").value = `${amount}`;
                document.getElementById("shippingFeeView").innerHTML = `${shipView} đ`;
                document.getElementById("shipping_fee").value = `${dataShip}`;
                document.getElementById("discountView").ind("discount").value = `${dataDiscount}`;
                document.getElementById("voucherId").value = null;
                this.getTotal();nerHTML = `${discountView} đ`;
                document.getElementByI
                $('#errVoucher').toggleClass("view-err");
                setTimeout(() => [$('#errVoucher').toggleClass("view-err")], 2000);
            } else {
                if (amount > data['min_order_value']) {
                    if (data['type'] == 2) {
                        this.getFee(1);
                    } else {
                        const totalDiscount = discount + (amount * data['discount_percent'] / 100) + data['discount_value']
                        const amountNew = amount - totalDiscount;
                        const discountView = VND.format(totalDiscount);
                        document.getElementById("amountDiscount").value = `${amountNew}`;
                        document.getElementById("discountView").innerHTML = `${discountView} đ`;
                        document.getElementById("discount").value = `${totalDiscount}`;
                        this.getTotal();
                    }
                    document.getElementById("voucherId").value = `${data['id']}`;
                } else {
                    const shipView = VND.format(dataShip);
                    const discountView = VND.format(dataDiscount);
                    document.getElementById("amountDiscount").value = `${amount}`;
                    document.getElementById("shippingFeeView").innerHTML = `${shipView} đ`;
                    document.getElementById("shipping_fee").value = `${dataShip}`;
                    document.getElementById("discountView").innerHTML = `${discountView} đ`;
                    document.getElementById("discount").value = `${dataDiscount}`;
                    document.getElementById("voucherId").value = null;
                    this.getTotal();
                    $('#errVoucher').toggleClass("view-err");
                    setTimeout(() => [$('#errVoucher').toggleClass("view-err"), document.getElementById("btnVoucher").disabled = false], 2000);
                }
            }
        })
    } else {
        const shipView = VND.format(dataShip);
        const discountView = VND.format(dataDiscount);
        document.getElementById("amountDiscount").value = `${amount}`;
        document.getElementById("shippingFeeView").innerHTML = `${shipView} đ`;
        document.getElementById("shipping_fee").value = `${dataShip}`;
        document.getElementById("discountView").innerHTML = `${discountView} đ`;
        document.getElementById("discount").value = `${dataDiscount}`;
        document.getElementById("voucherId").value = null;
        this.getTotal();
    }
}

function getTotal() {
    const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
    const amount = +document.getElementById('amountDiscount').value;
    const shippingFee = +document.getElementById('shipping_fee').value;
    const total = amount + shippingFee;
    const totalView = VND.format(total);
    console.log(amount);
    document.getElementById("total_amount").value = `${total}`;
    document.getElementById("totalAmountView").innerHTML = `${totalView} đ`;
}

Array.prototype.forEach.call(document.querySelectorAll('.inputfile'), function (input) {
    var label = input.nextElementSibling,
        labelVal = label.innerHTML;
    input.addEventListener('change', function (e) {
        var fileName = '';
        if (this.files && this.files.length > 1)
            fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
        else
            fileName = e.target.value.split('\\').pop();
        if (fileName)
            label.querySelector('span').innerHTML = fileName;
        else
            label.innerHTML = labelVal;
    });
});
