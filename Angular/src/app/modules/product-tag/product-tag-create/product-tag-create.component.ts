import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {ProductTagMeta} from '../product-tag.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ProductTagService} from '../product-tag.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-product-tag-create',
  templateUrl: './product-tag-create.component.html',
  styleUrls: ['./product-tag-create.component.css'],
  providers: [ProductTagService]
})
export class ProductTagCreateComponent extends AbstractModalComponent<ProductTagMeta> {

  constructor(
    service: ProductTagService,
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
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên tag', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

}
