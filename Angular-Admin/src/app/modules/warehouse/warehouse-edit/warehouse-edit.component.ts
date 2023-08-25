import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {WarehouseMeta} from '../warehouse.meta';
import {FieldForm} from '../../../core/common';
import {WarehouseService} from '../warehouse.service';

@Component({
  selector: 'app-warehouse-edit',
  templateUrl: './warehouse-edit.component.html',
  styleUrls: ['./warehouse-edit.component.css'],
  providers: [WarehouseService]
})
export class WarehouseEditComponent extends AbstractModalComponent<WarehouseMeta> {

  constructor(
    service: WarehouseService,
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
      weight: new FormControl(null, [Validators.required, Validators.min(0)]),
      quantity: new FormControl(null, [Validators.required, Validators.min(0), Validators.maxLength(10), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Khối lượng (kg)', 'weight', 'Nhập ký tự'),
      FieldForm.createTextInput('Số lượng', 'quantity', 'Nhập ký tự'),
    ];
  }

  loadAllCategories() {
    return this.service.loadAll();
  }

  loaded(): void {
  }
}
