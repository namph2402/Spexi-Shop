import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StoreMeta} from '../store.meta';
import {StoreService} from '../store.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-store-edit',
  templateUrl: './store-edit.component.html',
  styleUrls: ['./store-edit.component.css'],
  providers: [StoreService]
})
export class StoreEditComponent extends AbstractModalComponent<StoreMeta> {

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
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Giá trị', 'value', 'Nhập giá trị'),
    ];
  }

  loaded(): void {
  }


}
