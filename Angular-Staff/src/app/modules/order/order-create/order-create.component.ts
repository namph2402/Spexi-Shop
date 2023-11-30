import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { OrderMeta } from '../order.meta';
import { OrderService } from '../order.service';
import { AbstractModalComponent, FieldForm, ObjectUtil } from '../../../core';
import { VoucherService } from '../../voucher/voucher.service';
import { ProductService } from '../../product/product.service';
import { ProductMeta } from '../../product/product.meta';
import { CartItemMeta } from '../cart-item.meta';
import { VoucherMeta } from '../../voucher/voucher.meta';
import { ProvinceMeta } from '../../province/province.meta';
import { DistrictMeta } from '../../district/district.meta';
import { ProvinceService } from '../../province/province.service';
import { WardMeta } from '../../ward/ward.meta';
import { ShippingFeeMeta } from '../../shipping-fee/shipping-fee.meta';
import { ShippingFeeService } from '../../shipping-fee/shipping-fee.service';
import { CustomerService } from '../../customer/customer.service';
import { CustomerMeta } from '../../customer/customer.meta';
import { PaymentMethodService } from '../../payment-method/payment-method.service';

@Component({
  selector: 'app-order-create',
  templateUrl: './order-create.component.html',
  styleUrls: ['./order-create.component.css'],
  providers: [CustomerService, OrderService, VoucherService, ProductService, ProvinceService, ShippingFeeService, PaymentMethodService]
})
export class OrderCreateComponent extends AbstractModalComponent<OrderMeta> {

  amount: number = 0;
  shipFee: number = 0;
  shipFeeOld: number = 0;
  discount: number = 0;
  totalAmount: number = 0;

  voucher: VoucherMeta[] = [];
  arrProduct: CartItemMeta[] = [];
  productList: ProductMeta[];

  constructor(
    service: OrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService,
    private voucherService: VoucherService,
    private provinceService: ProvinceService,
    private shipFeeService: ShippingFeeService,
    private customerService: CustomerService,
    private paymentervice: PaymentMethodService
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadVouchers() {
    return this.voucherService.loadByParams({ status: 1 });
  }

  loadPayments() {
    return this.paymentervice.loadByParams({ status: 1 });
  }

  loadAllProvinces() {
    return this.provinceService.loadAll();
  }

  loadAllProducts() {
    return this.productService.loadByParams({ status: 1 });
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      customer_name: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      customer_phone: new FormControl(null, [Validators.required, Validators.maxLength(10), Validators.pattern('^(0)[0-9]{9}$')]),
      customer_address: new FormControl(null, [Validators.maxLength(255), Validators.required]),
      province: new FormControl(null, Validators.required),
      district: new FormControl(null, Validators.required),
      ward: new FormControl(null, Validators.required),
      customer_request: new FormControl(null, Validators.maxLength(255)),
      product: new FormControl(null, Validators.required),
      shipping_fee: new FormControl(null, [Validators.required, Validators.min(0)]),
      voucher_id: new FormControl(null),
      voucher_list: new FormControl(null),
      amount: new FormControl(null),
      discount: new FormControl(null),
      total_amount: new FormControl(null),
      payment_type: new FormControl(null, Validators.required),
      payment_status: new FormControl(null),
      type: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên khách hàng', 'customer_name', 'Nhập kí tự'),
      FieldForm.createNumberInput('Số điện thoại khách hàng', 'customer_phone', 'Nhập số'),
      FieldForm.createTextInput('Địa chỉ khách hàng', 'customer_address', 'Nhập kí tự'),
      FieldForm.createSingleSelect2('Tỉnh/Thành phố', 'province', '', 'loadAllProvinces'),
      FieldForm.createSingleSelect2('Huyện/Quận', 'district', '', []),
      FieldForm.createSingleSelect2('Xã/Phường', 'ward', '', []),
      FieldForm.createTextArea('Yêu cầu của khách hàng', 'customer_request', 'Nhập kí tự', 5),
      FieldForm.createSingleSelect2('Sản phẩm', 'product', 'Chọn một', 'loadAllProducts'),
      FieldForm.createSingleSelect2('Mã giảm giá', 'voucher_list', 'Chọn một', 'loadVouchers'),
      FieldForm.createSelect('Hình thức thanh toán', 'payment_type', 'Chọn một', 'loadPayments'),
    ];
  }

  loaded(): void {
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['customer_phone'].valueChanges.debounceTime(1000).subscribe((value: string) => {
      if (value && value.length == 10) {
        this.customerService.loadByParams({ phoneOrder: value }).subscribe((customers: CustomerMeta[]) => {
          if (customers.length > 0) {
            let c: CustomerMeta = customers[0];
            let provinceObj = this.fields[3].data.filter(v => v.name == c.province);
            let districtObj, wardObj;
            if (provinceObj.length > 0) {
              this.fields[4].data = provinceObj[0]['districts'];
              districtObj = this.fields[4].data.filter(v => v.name == c.district);
              if (districtObj.length > 0) {
                this.fields[5].data = districtObj[0]['wards'];
                wardObj = this.fields[5].data.filter(v => v.name == c.ward);
              }
            }
            let dataForm: any = {
              customer_name: c.fullname,
              customer_address: c.address,
              province: provinceObj,
              district: districtObj,
              ward: wardObj
            };
            Object.keys(dataForm).forEach(v => this.setFormValueByKey(v, dataForm[v], { emitEvent: false }));
            if (wardObj.length > 0) {
              this.shipFeeService.loadByParams({ ward_id: wardObj[0].id }).subscribe((shipFees: ShippingFeeMeta[]) => {
                if (shipFees.length > 0) {
                  let s: ShippingFeeMeta = shipFees[0];
                  this.shipFee = this.shipFeeOld = s.fee;
                  this.updateCart();
                }
              });
            }
          }
        });
      }
    });
    this.formGroup.controls['province'].valueChanges.subscribe((value: ProvinceMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['district'].setValue(null);
        this.formGroup.controls['ward'].setValue(null);
        this.fields[4].data = value[0].districts;
        this.fields[5].data = [];
        this.shipFee = 0;
      }
    });
    this.formGroup.controls['district'].valueChanges.subscribe((value: DistrictMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['ward'].setValue(null);
        this.fields[5].data = value[0].wards;
      }
    });
    this.formGroup.controls['ward'].valueChanges.subscribe((value: WardMeta[]) => {
      if (value && value.length > 0) {
        this.shipFeeService.loadByParams({ ward_id: value[0].id }).subscribe((shipFees: ShippingFeeMeta[]) => {
          if (shipFees.length > 0) {
            let s: ShippingFeeMeta = shipFees[0];
            this.shipFee = this.shipFeeOld = s.fee;
            this.updateCart();
          }
        });
      }
    });
    this.formGroup.controls['voucher_list'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.voucher = value;
      } else {
        this.voucher = []
      }
      this.updateCart();
    });
  }

  addProduct(productList: ProductMeta[]) {
    if (productList && productList.length > 0) {
      this.arrProduct.push({
        product: productList[0],
        warehouse_id: 0,
        unit_price: productList[0].sale_price,
        quantity: 1,
      });
      this.updateCart();
    }
  }

  checkArr(warehouse, i) {
    const test = this.arrProduct.filter(p => p.warehouse_id == warehouse);
    if (test.length >= 2) {
      test[0].quantity = test[0].quantity + test[1].quantity;
      this.arrProduct.splice(i, 1);
      this.updateCart();
    }
  }

  updateCart() {
    let quantity = 0;
    this.amount = 0;
    this.arrProduct.map(v => v.quantity).forEach(v => {
      quantity += v;
    });
    this.arrProduct.map(v => v.unit_price * v.quantity).forEach(v => {
      this.amount = this.amount + v;
    });
    this.checkVoucher();
  }

  checkVoucher() {
    this.discount = 0;
    this.shipFee = this.shipFeeOld;
    this.formGroup.controls['voucher_id'].setValue(null);
    if (this.voucher.length > 0) {
      let v: VoucherMeta = this.voucher[0];
      if (Date.parse(v.expired_date) > Date.now()) {
        if (this.amount < v.min_order_value) {
          this.service.toastError('Không đủ điều kiện áp dụng');
        } else {
          if (v.type == 2) {
            this.shipFee = 0;
          } else {
            if (this.amount <= v.discount_value) {
              this.discount = this.amount;
            } else {
              this.discount = (this.amount * v.discount_percent / 100) + v.discount_value;
            }
          }
          this.formGroup.controls['voucher_id'].setValue(v.id);
        }
      }
    }
    this.checkout();
  }

  checkout() {
    let amountT = this.amount - this.discount;
    if (amountT < 0) {
      amountT = 0;
    }
    this.totalAmount = amountT + this.shipFee
    this.formGroup.controls['amount'].setValue(this.amount);
    this.formGroup.controls['shipping_fee'].setValue(this.shipFee);
    this.formGroup.controls['discount'].setValue(this.discount);
    this.formGroup.controls['total_amount'].setValue(this.totalAmount);
  }

  deleteArr(i) {
    this.arrProduct.splice(i, 1);
    if (this.arrProduct.length == 0) {
      this.formGroup.controls['product'].setValue(null);
    }
    this.updateCart();
  }

  create() {
    let status = 0;
    for (let i of this.arrProduct) {
      const list: any = i.product.warehouses;
      if (i.quantity == 0 || i.warehouse_id == 0) {
        this.service.toastError("Cần nhập đủ thông tin sản phẩm");
        status = 1;
        break
      } else {
        const t = list.filter(w => w.id == i.warehouse_id);
        if (t[0].quantity < i.quantity) {
          this.service.toastError("Sản phẩm " + i.product.code + " không đủ hàng");
          status = 1;
          break
        }
      }
    }
    if (status == 0) {
      let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
      item.province = this.getFormValue('province')[0].name;
      item.district = this.getFormValue('district')[0].name;
      item.ward = this.getFormValue('ward')[0].name;
      item.payment_status = 1;
      item.type = 1
      item.product = JSON.stringify(this.arrProduct);
      this.service.store(item).subscribe(res => {
        this.service.toastSuccessfullyCreated();
        this.close(res);
      }, () => this.service.toastFailedCreated());
    }
  }
}
