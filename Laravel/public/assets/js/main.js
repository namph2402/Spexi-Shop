(function ($) {
    "use strict";
    new WOW().init();

    $(document).on("keypress", "form", function (event) {
        return event.keyCode != 13;
    });

    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    var toast = function () {
        setTimeout(function () {
            if ($('#toast-msg').length > 0) {
                document.querySelector("#toast-msg").remove();
            }
        }, 4000);
    };
    toast();

    $(document).ready(function () {
        var sendCode = function () {
            let remainTimeout = 40;
            if ($('#timeCode').length > 0) {
                setInterval(() => {
                    remainTimeout--;
                    if (remainTimeout > 0) {
                        document.getElementById("timeSecond").innerHTML = remainTimeout + 's';
                    } else if (remainTimeout == 0) {
                        document.querySelector("#timeCode").remove();
                        document.getElementById("sendCode").style.display = 'block';
                    }
                }, 1000);
            }
        };
        sendCode();

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

        $('.quantityDetail a').on('click', function () {
            var a = $(this);
            var oldValue = a.parent().parent().find('input').val();

            if (a.hasClass('btn-plus')) {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                if (oldValue > 0) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 0;
                }
            }
            a.parent().parent().find('input').val(newVal);
            onDetailChange();
        });

        $('#formSearch').on('submit', function (e) {
            if ($('input[name=search]', '#formSearch').val().trim().length == 0) {
                e.preventDefault();
            }
        });

        $('#formSearchPost').on('submit', function (e) {
            if ($('input[name=search]', '#formSearchPost').val().trim().length == 0) {
                e.preventDefault();
            }
        });

        $('#formEmail').on('submit', function (e) {
            if ($('input[name=email]', '#formEmail').val().trim().length == 0) {
                e.preventDefault();
            }
        });

        $('#formComment').on('submit', function (e) {
            if ($('input[name=rating]', '#formComment').val().trim().length == 0 || $('input[name=content]', '#formComment').val().trim().length == 0) {
                e.preventDefault();
            }
        });

        $('#capcha').on('submit', function (e) {
            if ($('input[name=code]', '#capcha').val().trim().length == 0) {
                e.preventDefault();
            }
        });

        $('#retrieval').on('submit', function (e) {
            if ($('input[name=user]', '#retrieval').val().trim().length == 0) {
                e.preventDefault();
            }
        });

        $('#formSearchP').on('submit', function (e) {
            var arrItemColor = [];
            var arrItemSize = [];
            var colorBox = $('.colorItem:checkbox:checked');
            var sizeBox = $('.sizeItem:checkbox:checked');
            for (var i = 0; i < colorBox.length; i++) {
                arrItemColor.push(colorBox[i].value);
            }

            for (var i = 0; i < sizeBox.length; i++) {
                    arrItemSize.push(sizeBox[i].value);
            }

            if (arrItemColor.length > 0) {
                document.getElementById('color').value = arrItemColor;
            }
            
            if (arrItemSize.length > 0) {
                document.getElementById('size').value = arrItemSize;
            }
        })

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

        let rangeInput = document.querySelectorAll('.range-input input');
        if (rangeInput.length > 0) {
            let rangeText = document.querySelectorAll('.range-text div');
            let progress = document.querySelector('.progress1');
            let priceMax = rangeInput[0].max;
            let priceGap = 10000;

            rangeInput.forEach(input => {
                if (parseInt(rangeInput[0].value) != 0 || parseInt(rangeInput[1].value) != 1000000) {
                    let minVal = parseInt(rangeInput[0].value);
                    let maxVal = parseInt(rangeInput[1].value);

                    if (maxVal - minVal < priceGap) {
                        minVal = rangeInput[0].value = maxVal - priceGap;
                        maxVal = rangeInput[1].value = minVal + priceGap;
                    }

                    let positionMin = ((minVal / priceMax) * 100).toFixed();
                    let positionMax = (100 - ((maxVal / priceMax) * 100)).toFixed();

                    progress.style.left = positionMin + '%';
                    progress.style.right = positionMax + '%';
                    rangeText[0].style.left = positionMin + '%';
                    rangeText[1].style.right = positionMax + '%';
                    rangeText[0].innerText = minVal.toLocaleString();
                    rangeText[1].innerText = maxVal.toLocaleString();
                }

                input.addEventListener('input', (event) => {
                    let minVal = parseInt(rangeInput[0].value);
                    let maxVal = parseInt(rangeInput[1].value);

                    if (maxVal - minVal < priceGap) {
                        if (event.target.className === 'range-min') {
                            minVal = rangeInput[0].value = maxVal - priceGap;
                        } else {
                            maxVal = rangeInput[1].value = minVal + priceGap;
                        }
                    }

                    let positionMin = ((minVal / priceMax) * 100).toFixed();
                    let positionMax = (100 - ((maxVal / priceMax) * 100)).toFixed();

                    progress.style.left = positionMin + '%';
                    progress.style.right = positionMax + '%';
                    rangeText[0].style.left = positionMin + '%';
                    rangeText[1].style.right = positionMax + '%';
                    rangeText[0].innerText = minVal.toLocaleString();
                    rangeText[1].innerText = maxVal.toLocaleString();
                })
            })
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        Validator({
            form: '#signup',
            formGroupSelector: '.account-group',
            errorSelector: '.error-message',
            rules: [
                Validator.isUserName('#username'),
                Validator.isEmail('#email'),
                Validator.minLength('#password', 6),
                Validator.isRequired('#re_password'),
                Validator.isConfirmed('#re_password', function () {
                    return document.querySelector('#signup #password').value;
                }, 'Mật khẩu nhập lại không chính xác')
            ],
        });

        Validator({
            form: '#signin',
            formGroupSelector: '.account-group',
            errorSelector: '.error-message',
            rules: [
                Validator.isRequired('#username'),
                Validator.minLength('#password', 6),
            ],
        });

        Validator({
            form: '#updateProfile',
            formGroupSelector: '.form-group',
            errorSelector: '.error-message',
            rules: [
                Validator.isName('#fullname'),
                Validator.isPhone('#phone'),
                Validator.isRequired('#ward_id'),
                Validator.isRequired('#address'),
            ],
        });

        Validator({
            form: '#formCheckout',
            formGroupSelector: '.form-group',
            errorSelector: '.error-message',
            rules: [
                Validator.isName('#customer_name'),
                Validator.isPhone('#customer_phone'),
                Validator.isRequired('#customer_address'),
                Validator.isRequired('#province_id'),
                Validator.isRequired('#district_id'),
                Validator.isRequired('#ward_id'),
            ],
        });

        Validator({
            form: '#updatePassword',
            formGroupSelector: '.form-group',
            errorSelector: '.error-message',
            rules: [
                Validator.minLength('#oldPassword', 6),
                Validator.minLength('#password', 6),
                Validator.isRequired('#re_password'),
                Validator.isConfirmed('#re_password', function () {
                    return document.querySelector('#updatePassword #password').value;
                }, 'Mật khẩu nhập lại không chính xác')
            ],
        });

    });

})(jQuery);

const urlParams = new URLSearchParams(window.location.search);
function setParamsPage(name, value) {
    urlParams.set(name, value);
    window.location.search = urlParams
}

function Validator(options) {
    function getParent(element, selector) {
        while (element.parentElement) {
            if (element.parentElement.matches(selector)) {
                return element.parentElement;
            }
            element = element.parentElement;
        }
    }

    var selectorRules = {};

    function validate(inputElement, rule) {
        var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
        var errorMessage;

        var rules = selectorRules[rule.selector];

        for (var i = 0; i < rules.length; ++i) {
            switch (inputElement.type) {
                case 'radio':
                case 'checkbox':
                    errorMessage = rules[i](
                        formElement.querySelector(rule.selector + ':checked')
                    );
                    break;
                default:
                    errorMessage = rules[i](inputElement.value);
            }
            if (errorMessage) break;
        }

        if (errorMessage) {
            errorElement.innerText = errorMessage;
            getParent(inputElement, options.formGroupSelector).classList.add('invalid');
        } else {
            errorElement.innerText = '';
            getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
        }

        return !errorMessage;
    }

    var formElement = document.querySelector(options.form);
    if (formElement) {

        formElement.onsubmit = function (e) {
            e.preventDefault();

            var isFormValid = true;

            options.rules.forEach(function (rule) {
                var inputElement = formElement.querySelector(rule.selector);
                var isValid = validate(inputElement, rule);
                if (!isValid) {
                    isFormValid = false;
                }
            });

            if (isFormValid) {
                if (typeof options.onSubmit === 'function') {
                    var enableInputs = formElement.querySelectorAll('[name]');
                    var formValues = Array.from(enableInputs).reduce(function (values, input) {

                        switch (input.type) {
                            case 'radio':
                                values[input.name] = formElement.querySelector('input[name="' + input.name + '"]:checked').value;
                                break;
                            case 'checkbox':
                                if (!input.matches(':checked')) {
                                    values[input.name] = '';
                                    return values;
                                }
                                if (!Array.isArray(values[input.name])) {
                                    values[input.name] = [];
                                }
                                values[input.name].push(input.value);
                                break;
                            case 'file':
                                values[input.name] = input.files;
                                break;
                            default:
                                values[input.name] = input.value;
                        }

                        return values;
                    }, {});
                    options.onSubmit(formValues);
                }
                else {
                    formElement.submit();
                }
            }
        }

        options.rules.forEach(function (rule) {

            if (Array.isArray(selectorRules[rule.selector])) {
                selectorRules[rule.selector].push(rule.test);
            } else {
                selectorRules[rule.selector] = [rule.test];
            }

            var inputElements = formElement.querySelectorAll(rule.selector);

            Array.from(inputElements).forEach(function (inputElement) {

                inputElement.onblur = function () {
                    validate(inputElement, rule);
                }

                inputElement.oninput = function () {
                    var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
                    errorElement.innerText = '';
                    getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
                }
            });
        });
    }

}

Validator.isRequired = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            return value ? undefined : message || 'Vui lòng nhập đầy đủ thông tin'
        }
    };
}

Validator.isName = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$/;
            return regex.test(value) ? undefined : message || 'Vui lòng nhập tên của bạn';
        }
    };
}

Validator.isUserName = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^(?=.*[a-zA-Z0-9]+)[a-zA-Z0-9]*$/;
            return regex.test(value) ? undefined : message || 'Vui lòng nhập tên đăng nhập';
        }
    };
}

Validator.isEmail = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            return regex.test(value) ? undefined : message || 'Vui lòng nhập email của bạn';
        }
    };
}

Validator.isPhone = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^(0)[0-9]{9}$/;
            return regex.test(value) ? undefined : message || 'Vui lòng nhập số điện thoại của bạn';
        }
    };
}

Validator.minLength = function (selector, min, message) {
    return {
        selector: selector,
        test: function (value) {
            return value.length >= min ? undefined : message || `Vui lòng nhập tối thiểu ${min} kí tự`;
        }
    };
}

Validator.isConfirmed = function (selector, getConfirmValue, message) {
    return {
        selector: selector,
        test: function (value) {
            return value === getConfirmValue() ? undefined : message || 'Giá trị nhập vào không chính xác';
        }
    }
}

function onDetailChange() {
    var a = $('input[name=quantity]', '#formDetail').val();
    var p = $('input[name=product_id]', '#formDetail').val();
    var s = $('input[name=size_id]:checked', '#formDetail').val();
    var c = $('input[name=color_id]:checked', '#formDetail').val();

    if (s == undefined || c == undefined) {
        document.getElementById('btnDetail').disabled = true;
    } else {
        Checkout.getInstance().getWarehouse(p, s, c, (data) => {
            if(data['quantity'] == 0) {
                document.getElementById('errText').innerText = "Hết hàng trong kho";
                document.getElementById('btnDetail').disabled = true;
            } else {
                if(data['quantity'] < a) {
                    document.getElementById('errText').innerText = "Không đủ hàng";
                    document.getElementById('btnDetail').disabled = true;
                } else {
                    document.getElementById('errText').innerText = "";
                    document.getElementById('btnDetail').disabled = false;
                }
            }
        })
    }
}

function changeForm() {
    var form = document.getElementById('formSearchP');
    if(form.style.display == 'block') {
        form.style.display = 'none';
    } else {
        form.style.display = 'block';
    }
}

function sendCapcha(url) {
    const email = document.getElementById('emailCapcha').value;
    const href = `/send-capcha?email=${email}`
    window.location.href = url + href;
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
    const shipView = document.getElementById("shippingFeeView");
    const shipFee = document.getElementById("shipping_fee");

    if (status == 0 && promotion != 3) {
        Checkout.getInstance().getShipFee(provinceId, districtId, wardId, (data) => {
            const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
            const shipping_fee = VND.format(data.fee);
            shipView.innerHTML = `${shipping_fee} đ`;
            shipFee.value = `${data['fee']}`;
        })
    } else {
        const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
        const shipping_fee = VND.format('0');
        shipView.innerHTML = `${shipping_fee} đ`;
        shipFee.value = `0`;
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
    const shipView = VND.format(dataShip);
    const discountView = VND.format(dataDiscount);
    const voucherId = document.getElementById("voucherId");

    if (code.trim().length > 0 && !voucherId.value) {
        Checkout.getInstance().getVoucher(code, (data) => {
            if (data && amount > data['min_order_value']) {
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
                voucherId.value = `${data['id']}`;
                document.getElementById("voucher").disabled = true;
                document.getElementById("btnVoucher").hidden = true;
            } else {
                document.getElementById("amountDiscount").value = `${amount - dataDiscount}`;
                document.getElementById("shippingFeeView").innerHTML = `${shipView} đ`;
                document.getElementById("shipping_fee").value = `${dataShip}`;
                document.getElementById("discountView").innerHTML = `${discountView} đ`;
                document.getElementById("discount").value = `${dataDiscount}`;
                voucherId.value = null;
                this.getTotal();
                $('#errVoucher').toggleClass("d-block");
                setTimeout(() => [$('#errVoucher').toggleClass("d-block")], 2000);
            }
        })
    } else {
        document.getElementById("amountDiscount").value = `${amount - dataDiscount}`;
        document.getElementById("shippingFeeView").innerHTML = `${shipView} đ`;
        document.getElementById("shipping_fee").value = `${dataShip}`;
        document.getElementById("discountView").innerHTML = `${discountView} đ`;
        document.getElementById("discount").value = `${dataDiscount}`;
        voucherId.value = null;
        this.getTotal();
    }
}

function getTotal() {
    const VND = new Intl.NumberFormat('vi-VN', { tyle: 'currency', currency: 'VND', });
    const amount = +document.getElementById('amountDiscount').value;
    const shippingFee = +document.getElementById('shipping_fee').value;
    const total = amount + shippingFee;
    const totalView = VND.format(total);
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
