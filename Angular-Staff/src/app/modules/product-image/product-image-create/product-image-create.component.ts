import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductImageMeta} from '../product-image.meta';
import {ProductImageService} from '../product-image.service';

@Component({
  selector: 'app-product-image-create',
  templateUrl: './product-image-create.component.html',
  styleUrls: ['./product-image-create.component.css'],
  providers: [ProductImageService]
})
export class ProductImageCreateComponent extends AbstractModalComponent<ProductImageMeta> {

  constructor(
    service: ProductImageService,
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
      product_id: new FormControl(null),
      image: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createFileInput('Ảnh', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
    ];
  }

  loaded(): void {
    this.formGroup.controls['product_id'].setValue(this.model.product_id);
  }
}
