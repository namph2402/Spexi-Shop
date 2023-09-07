import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { OrderService } from '../order.service';
import { OrderMeta } from '../order.meta';
import { ProvinceMeta } from '../../province/province.meta';
import { DistrictMeta } from '../../district/district.meta';
import { AbstractModalComponent, FieldForm, ObjectUtil } from '../../../core';
import { VoucherService } from '../../voucher/voucher.service';
import { ProductService } from '../../product/product.service';
import { CartItemMeta } from '../cart-item.meta';
import { ProductMeta } from '../../product/product.meta';
import { VoucherMeta } from '../../voucher/voucher.meta';
import { ProvinceService } from '../../province/province.service';
import { WardMeta } from '../../ward/ward.meta';
import { ShippingFeeMeta } from '../../shipping-fee/shipping-fee.meta';
import { ShippingFeeService } from '../../shipping-fee/shipping-fee.service';
import { PaymentMethodService } from '../../payment-method/payment-method.service';

@Component({
  selector: 'app-order-edit',
  templateUrl: './order-edit.component.html',
  styleUrls: ['./order-edit.component.css'],
  providers: [OrderService, VoucherService, ProductService, ProvinceService, ShippingFeeService, PaymentMethodService]
})
export class OrderEditComponent extends AbstractModalComponent<OrderMeta> {

  amount: number = 0;
  shipFee: number = 0;
  shipFeeOld: number = 0;
  discount: number = 0;
  totalAmount: number = 0;
  voucherId: any;

  statusMain: boolean = false;
  voucherI: VoucherMeta[] = [];
  arrProduct: CartItemMeta[] = [];
  productList: ProductMeta[];

  constructor(
    service: OrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService,
    private voucherService: VoucherService,
    private provinceService: ProvinceService,
    private paymentervice: PaymentMethodService,
    private shipFeeService: ShippingFeeService
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
      payment_status: new FormControl(null, Validators.required),
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
      FieldForm.createSelect('Trạng thái thanh toán', 'payment_status', '', [
        {
          name: "Chưa thanh toán",
          value: "0"
        },
        {
          name: "Đã thanh toán",
          value: "1"
        }
      ]),
    ];
  }

  public setFormValue() {
    let item: any = ObjectUtil.clone(this.model);
    let provinceObj = this.fields[3].data.filter(v => v.name == this.model.province);
    if (provinceObj.length > 0) {
      item.province = provinceObj;
      this.fields[4].data = provinceObj[0]['districts'];
      let districtObj = this.fields[4].data.filter(v => v.name == this.model.district);
      if (districtObj.length > 0) {
        item.district = districtObj;
        this.fields[5].data = districtObj[0]['wards'];
        let wardObj = this.fields[5].data.filter(v => v.name == this.model.ward);
        if (districtObj.length > 0) {
          item.ward = wardObj;
        }
      }
    }
    this.formGroup.get(this.fields[7].formControl).setValue([this.arrProduct[0].product]);
    if (this.model.voucher != null) {
      this.formGroup.get(this.fields[8].formControl).setValue([this.model.voucher]);
    }
    this.formGroup.get(this.fields[9].formControl).setValue([this.model.payment_type]);
    this.formGroup.get(this.fields[10].formControl).setValue([this.model.payment_status]);
    this.formGroup.patchValue(ObjectUtil.mergeValue(this.formGroup.value, item));
    this.statusMain = true;
  }

  loaded(): void {
    for (let i of this.model.details) {
      let cartItemMeta = new CartItemMeta();
      cartItemMeta.product = i.product;
      cartItemMeta.quantity = i.quantity;
      cartItemMeta.unit_price = i.unit_price;
      cartItemMeta.warehouse_id = i.warehouse_id;
      this.arrProduct.push(cartItemMeta);
    }
  }

  onFormChanged(): void {
    super.onFormChanged();
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
        this.voucherI = value;
      } else {
        this.voucherI = []
      }
      this.updateCart();
    });
  }

  addProduct(productList: ProductMeta[]) {
    if (this.statusMain == true && productList && productList.length > 0) {
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
    this.voucherId = null;
    if (this.voucherI.length > 0) {
      let v: VoucherMeta = this.voucherI[0];
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
          this.voucherId = v.id;
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

  edit() {
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
      item.product = JSON.stringify(this.arrProduct);
      item.voucher_id = this.voucherId ;
      this.service.update(item).subscribe(res => {
        this.service.toastSuccessfullyEdited();
        this.close(res);
      }, () => this.service.toastFailedEdited());
    }
  }
}
