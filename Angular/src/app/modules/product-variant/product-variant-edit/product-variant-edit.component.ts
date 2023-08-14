import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductVariantService} from '../product-variant.service';
import {ProductVariantMeta} from '../product-variant.meta';


@Component({
  selector: 'app-product-variant-edit',
  templateUrl: './product-variant-edit.component.html',
  styleUrls: ['./product-variant-edit.component.css'],
  providers: [ProductVariantService]
})
export class ProductVariantEditComponent extends AbstractModalComponent<ProductVariantMeta> {

  constructor(
    service: ProductVariantService,
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
