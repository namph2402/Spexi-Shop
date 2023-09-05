import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PromotionProductMeta} from '../promotion-product.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, ModalOptions} from 'ngx-bootstrap';
import {PromotionProductService} from '../promotion-product.service';
import {ProductService} from '../../product/product.service';
import {ProductMeta} from '../../product/product.meta';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm} from '../../../core/common';
import {ProductCategoryService} from '../../product-category/product-category.service';

@Component({
  selector: 'app-promotion-product-create',
  templateUrl: './promotion-product-create.component.html',
  styleUrls: ['./promotion-product-create.component.css'],
  providers: [PromotionProductService, ProductService, ProductCategoryService]
})
export class PromotionProductCreateComponent extends AbstractModalComponent<PromotionProductMeta> {

  items: any = [];
  listAvailableProducts: any;

  formSearchProduct: FormGroup = new FormGroup({
    search: new FormControl(null, Validators.maxLength(255)),
    category_id: new FormControl(null),
    status: new FormControl(null),
  });
  listItem: any;

  constructor(
    service: PromotionProductService,
    modalRef: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService,
    private categoryService: ProductCategoryService,
  ) {
    super(service, modalRef, builder);
    this.loadAllCategory();
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return null;
  }

  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
      category_id: new FormControl(null),
      status: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', ''),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', []),
    ];
  }

  initNewModel(): PromotionProductMeta {
    return null;
  }

  loaded(): void {
    this.load();
  }

  loadAllCategory() {
    return this.categoryService.loadAll().subscribe(value => {
      this.fields[1].data = value;
    });
  }

  load() {
    let param: any = ObjectUtil.combineValue({}, this.formGroup.value, true);
    this.productService.loadAvailableProducts(this.model.promotion_id, param).subscribe((res: any) => {
        this.listAvailableProducts = res;
      }, () => {
        this.listAvailableProducts = [];
      }
    );
  }

  assign(item: any, type: number) {
    if (type == 1) {
      this.listItem = item;
    }
    if (type == 2) {
      this.listItem = [item];
    }
    (<PromotionProductService>this.service).assignProducts(this.model.promotion_id, this.listItem).subscribe((res: ProductMeta) => {
      this.service.toastSuccessfully('Thêm mới thành công');
      this.load();
    }, () => this.service.toastFailed('Thêm mới thất bại'));
  }

  removeFilter() {
    this.formGroup.reset();
    this.load();
  }

}
