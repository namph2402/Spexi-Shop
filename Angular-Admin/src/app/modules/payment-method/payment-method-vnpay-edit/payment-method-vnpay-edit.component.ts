import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PaymentMethodMeta} from '../payment-method.meta';
import {PaymentMethodService} from '../payment-method.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-payment-method-vnpay-edit',
  templateUrl: './payment-method-vnpay-edit.component.html',
  styleUrls: ['./payment-method-vnpay-edit.component.css'],
  providers: [PaymentMethodService]
})
export class PaymentMethodEditVnpayComponent extends AbstractModalComponent<PaymentMethodMeta> {

  onParentChange() {
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('VNP Url *', 'vnp_Url', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP TmnCode *', 'vnp_TmnCode', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP HashSecret *', 'vnp_HashSecret', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP Locale *', 'vnp_Locale', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP Version *', 'vnp_Version', 'Nhập kí tự'),
    ];
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      vnp_Url: new FormControl(null, Validators.required),
      vnp_TmnCode: new FormControl(null, Validators.required),
      vnp_HashSecret: new FormControl(null, Validators.required),
      vnp_Locale: new FormControl(null, Validators.required),
      vnp_Version: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
  }

  constructor(
    service: PaymentMethodService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}
