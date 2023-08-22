import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StoreService} from '../store.service';
import {StoreMeta} from '../store.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-store-create',
  templateUrl: './store-create.component.html',
  styleUrls: ['./store-create.component.css'],
  providers: [StoreService]
})
export class StoreCreateComponent extends AbstractModalComponent<StoreMeta> {

  constructor(
    service: StoreService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      value: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createTextArea('Giá trị', 'value', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

}
