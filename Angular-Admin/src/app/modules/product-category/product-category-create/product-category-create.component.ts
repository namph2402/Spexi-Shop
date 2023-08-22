import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {ProductCategoryMeta} from '../product-category.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ProductCategoryService} from '../product-category.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-product-category-create',
  templateUrl: './product-category-create.component.html',
  styleUrls: ['./product-category-create.component.css'],
  providers: [ProductCategoryService]
})
export class ProductCategoryCreateComponent extends AbstractModalComponent<ProductCategoryMeta> {

  constructor(
    service: ProductCategoryService,
    modal: BsModalRef,
    builder: FormBuilder,
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
      parent: new FormControl(null),
      parent_id: new FormControl(0),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      image: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Danh mục cha', 'parent', 'Chọn một', 'loadAllCategories'),
      FieldForm.createTextInput('Tên danh mục', 'name', 'Nhập tên'),
      FieldForm.createFileInput('Ảnh đại diện', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
    ];
  }

  loaded(): void {
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['parent'].valueChanges.subscribe((value: ProductCategoryMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['parent_id'].setValue(value[0].id);
      }
    });
  }

}
