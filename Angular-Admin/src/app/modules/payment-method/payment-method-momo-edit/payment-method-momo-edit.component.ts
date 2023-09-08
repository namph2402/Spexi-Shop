import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PaymentMethodMeta} from '../payment-method.meta';
import {PaymentMethodService} from '../payment-method.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-payment-method-momo-edit',
  templateUrl: './payment-method-momo-edit.component.html',
  styleUrls: ['./payment-method-momo-edit.component.css'],
  providers: [PaymentMethodService]
})
export class PaymentMethodMomoEditComponent extends AbstractModalComponent<PaymentMethodMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      endpoint: new FormControl(null, Validators.required),
      partnerCode: new FormControl(null, Validators.required),
      accessKey: new FormControl(null, Validators.required),
      secretKey: new FormControl(null, Validators.required),
      type: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('MM Endpoint', 'endpoint', 'Nhập kí tự'),
      FieldForm.createTextInput('MM PartnerCode', 'partnerCode', 'Nhập kí tự'),
      FieldForm.createTextInput('MM AccessKey', 'accessKey', 'Nhập kí tự'),
      FieldForm.createTextInput('MM SecretKey', 'secretKey', 'Nhập kí tự'),
      FieldForm.createSelect('Loại thanh toán', 'type', 'Chọn một', [
        {
          name: 'ATM',
          value: 'payWithATM'
        },
        {
          name: 'QR',
          value: 'captureWallet'
        }
      ]),
    ];
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
