import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ProductMeta} from '../product.meta';
import {ProductService} from '../product.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {ProductCategoryService} from '../../product-category/product-category.service';
import {ProductCategoryMeta} from '../../product-category/product-category.meta';

@Component({
  selector: 'app-product-edit',
  templateUrl: './product-edit.component.html',
  styleUrls: ['./product-edit.component.css'],
  providers: [ProductService, ProductCategoryService]
})
export class ProductEditComponent extends AbstractModalComponent<ProductMeta> {

  constructor(
    service: ProductService,
    modal: BsModalRef,
    builder: FormBuilder,
    private categoryService: ProductCategoryService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllCategories() {
    return this.categoryService.loadByParams({child: 0});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      category: new FormControl(null, Validators.required),
      category_id: new FormControl(null),
      category_slug: new FormControl(null),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      code: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      summary: new FormControl(null),
      image: new FormControl(null, Validators.required),
      price: new FormControl(null, [Validators.required, Validators.min(0), Validators.maxLength(20), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect3('Danh mục', 'category', 'Chọn danh mục', 'loadAllCategories'),
      FieldForm.createTextInput('Tên sản phẩm', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Code', 'code', 'Nhập mã'),
      FieldForm.createFileInput('Ảnh đại diện', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
      FieldForm.createNumberInput('Giá sản phẩm', 'price', 'Nhập giá'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', {height: '300px'}),
    ];
  }

  loaded(): void {
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['category'].valueChanges.subscribe((value: ProductCategoryMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['category_id'].setValue(value[0].id);
        this.formGroup.controls['category_slug'].setValue(value[0].slug);
      }
    });
  }
}
