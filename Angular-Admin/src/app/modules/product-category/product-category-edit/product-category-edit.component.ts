import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductCategoryService} from '../product-category.service';
import {ProductCategoryMeta} from '../product-category.meta';

@Component({
  selector: 'app-product-category-edit',
  templateUrl: './product-category-edit.component.html',
  styleUrls: ['./product-category-edit.component.css'],
  providers: [ProductCategoryService]
})
export class ProductCategoryEditComponent extends AbstractModalComponent<ProductCategoryMeta> {

  constructor(
    service: ProductCategoryService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllCategories() {
    return this.service.loadByParams({parent: 0});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      parent_id: new FormControl({value: null, disabled: true}),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      image: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Danh mục cha', 'parent_id', 'Danh mục', 'loadAllCategories'),
      FieldForm.createTextInput('Tên danh mục', 'name', 'Nhập tên'),
      FieldForm.createFileInput('Ảnh đại diện', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
    ];
  }

  loaded(): void {
  }
}
