import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductRelatedMeta} from '../product-related.meta';
import {ProductRelatedService} from '../product-related.service';
import {ProductMeta} from '../../product/product.meta';
import { ObjectUtil } from '../../../core';
import { ProductCategoryService } from '../../product-category/product-category.service';

@Component({
  selector: 'app-product-related-create',
  templateUrl: './product-related-create.component.html',
  styleUrls: ['./product-related-create.component.css'],
  providers: [ProductRelatedService, ProductCategoryService]
})
export class ProductRelatedCreateComponent extends AbstractModalComponent<ProductRelatedMeta> {

  list = [];

  formSearchProduct: FormGroup = new FormGroup({
    search: new FormControl(null, Validators.maxLength(255)),
    category_id: new FormControl(null),
  });

  constructor(
    service: ProductRelatedService,
    modal: BsModalRef,
    builder: FormBuilder,
    private categoryService: ProductCategoryService,
  ) {
    super(service, modal, builder);
    this.loadAllCategory();
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
      category_id: new FormControl(null),
      product_id: new FormControl(null),
      related_id: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', '', 'col-md-6'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', [], 'col-md-6'),
    ];
  }

  loadAllCategory() {
    return this.categoryService.loadAll().subscribe(value => {
      this.fields[1].data = value;
    });
  }


  loaded(): void {
    this.load();
  }

  load(): void {
    this.formGroup.controls['product_id'].setValue(this.model.product_id);
    let param: any = ObjectUtil.combineValue({}, this.formGroup.value, true);
    (<ProductRelatedService>this.service).loadProducts(param).subscribe((res: any) => {
      this.list = res;
    }, () => {
      this.list = [];
    });
  }

  addRelated(item: ProductMeta) {
    let data: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    data['product_id'] = this.model.product_id;
    data['related_id'] = item.id;
    (<ProductRelatedService>this.service).addRelated(data).subscribe((res: any) => {
      this.service.toastSuccessfully('Thêm sản phẩm');
      this.load();
    }, () => this.service.toastFailed('Thêm sản phẩm'));
  }

  removeFilter() {
    this.formGroup.reset();
    this.load();
  }
}
