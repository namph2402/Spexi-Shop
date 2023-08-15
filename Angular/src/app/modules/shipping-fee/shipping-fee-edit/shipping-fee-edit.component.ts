import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingFeeMeta} from '../shipping-fee.meta';
import {ShippingFeeService} from '../shipping-fee.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping-fee-edit',
  templateUrl: './shipping-fee-edit.component.html',
  styleUrls: ['./shipping-fee-edit.component.css'],
  providers: [ShippingFeeService]
})
export class ShippingFeeEditComponent extends AbstractModalComponent<ShippingFeeMeta> {

  constructor(
    service: ShippingFeeService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      fee: new FormControl(null, [Validators.required, Validators.min(0)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Tiền ship', 'fee', 'Nhập tiền ship'),
    ];
  }
}
