import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {ShippingUnitMeta} from '../shipping-unit.meta';
import {ShippingUnitService} from '../shipping-unit.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping-unit-create',
  templateUrl: './shipping-unit-create.component.html',
  styleUrls: ['./shipping-unit-create.component.css'],
  providers: [ShippingUnitService]
})
export class ShippingUnitCreateComponent extends AbstractModalComponent<ShippingUnitMeta> {
  
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      username: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      password: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      token: new FormControl(null,[Validators.required, Validators.pattern('[^ ].*$')]),
      endpoint: new FormControl(null,[Validators.required, Validators.pattern('[^ ].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên đối tác vận chuyển', 'name', 'Nhập tên đối tác'),
      FieldForm.createTextInput('User name đăng nhập', 'username', 'Nhập tên đăng nhập'),
      FieldForm.createTextInput('Password', 'password', 'Nhập tên đăng nhập'),
      FieldForm.createTextInput('Token', 'token', 'Nhập ký tự'),
      FieldForm.createTextInput('Endpoint', 'endpoint', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ShippingUnitService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}
