import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductTagService} from '../product-tag.service';
import {ProductTagMeta} from '../product-tag.meta';

@Component({
  selector: 'app-product-tag-edit',
  templateUrl: './product-tag-edit.component.html',
  styleUrls: ['./product-tag-edit.component.css'],
  providers: [ProductTagService]
})
export class ProductTagEditComponent extends AbstractModalComponent<ProductTagMeta> {

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
      FieldForm.createTextInput('Tên tag *', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }
}
