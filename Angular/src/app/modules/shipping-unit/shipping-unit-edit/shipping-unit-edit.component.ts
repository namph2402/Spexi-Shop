import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingUnitService} from '../shipping-unit.service';
import {ShippingUnitMeta} from '../shipping-unit.meta';
import { AbstractModalComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';


@Component({
  selector: 'app-shipping-unit-edit',
  templateUrl: './shipping-unit-edit.component.html',
  styleUrls: ['./shipping-unit-edit.component.css'],
  providers: [ShippingUnitService]
})
export class ShippingUnitEditComponent extends AbstractModalComponent<ShippingUnitMeta> {

  onInit(): void {
  }


  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl({value: null, disabled: true}, Validators.required),
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
      FieldForm.createTextInput('Password', 'password', 'Nhập mật khẩu'),
      FieldForm.createTextInput('Token', 'token', 'Nhập ký tự'),
      FieldForm.createTextInput('Endpoint', 'endpoint', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ShippingUnitService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
