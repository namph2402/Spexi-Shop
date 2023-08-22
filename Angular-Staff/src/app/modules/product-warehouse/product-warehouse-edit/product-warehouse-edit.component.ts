import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductWarehouseService} from '../product-warehouse.service';
import {ProductWarehouseMeta} from '../product-warehouse.meta';

@Component({
  selector: 'app-product-warehouse-edit',
  templateUrl: './product-warehouse-edit.component.html',
  styleUrls: ['./product-warehouse-edit.component.css'],
  providers: [ProductWarehouseService]
})
export class ProductWarehouseEditComponent extends AbstractModalComponent<ProductWarehouseMeta> {

  constructor(
    service: ProductWarehouseService,
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
      weight: new FormControl(null, Validators.min(0)),
      quantity: new FormControl(null, [Validators.min(0), Validators.maxLength(10), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Khối lượng (kg)', 'weight', 'Nhập ký tự'),
      FieldForm.createTextInput('Số lượng', 'quantity', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }


}
