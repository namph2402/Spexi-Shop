import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductVariantMeta} from '../product-variant.meta';
import {ProductVariantService} from '../product-variant.service';
import {ProductSizeService} from '../../product-size/product-size.service';
import {ProductColorService} from '../../product-color/product-color.service';

@Component({
  selector: 'app-product-variant-create',
  templateUrl: './product-variant-create.component.html',
  styleUrls: ['./product-variant-create.component.css'],
  providers: [ProductVariantService, ProductSizeService, ProductColorService]
})
export class ProductVariantCreateComponent extends AbstractModalComponent<ProductVariantMeta> {

  constructor(
    service: ProductVariantService,
    modal: BsModalRef,
    builder: FormBuilder,
    protected sizeService: ProductSizeService,
    protected colorService: ProductColorService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllSizes() {
    return this.sizeService.loadByParams({parent: 0});
  }

  loadAllColors() {
    return this.colorService.loadByParams({parent: 0});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      product_id: new FormControl(null),
      sizes: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      colors: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      sizeArr: new FormControl(null, Validators.required),
      colorArr: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createMultiSelect2('Size', 'sizes', 'Chọn một', 'loadAllSizes'),
      FieldForm.createMultiSelect2('Color', 'colors', 'Chọn một', 'loadAllColors'),
    ];
  }

  loaded(): void {
    this.formGroup.controls['product_id'].setValue(this.model.product_id);
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['sizes'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['sizeArr'].setValue(value.map(v => v.id));
      } else {
        this.formGroup.controls['sizeArr'].setValue(null);
      }
    });
    this.formGroup.controls['colors'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['colorArr'].setValue(value.map(v => v.id));
      } else {
        this.formGroup.controls['colorArr'].setValue(null);
      }
    });
  }
}
