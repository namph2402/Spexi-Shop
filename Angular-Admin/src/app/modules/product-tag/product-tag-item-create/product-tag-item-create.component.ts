import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm, ModalResult} from '../../../core/common';
import {ProductService} from '../../product/product.service';
import {ProductMeta} from '../../product/product.meta';
import {ObjectUtil} from '../../../core';
import {ProductTagService} from '../../product-tag/product-tag.service';
import {ProductTagMeta} from '../product-tag.meta';
import {ProductCategoryService} from '../../product-category/product-category.service';

@Component({
  selector: 'app-product-tag-item-create',
  templateUrl: './product-tag-item-create.component.html',
  styleUrls: ['./product-tag-item-create.component.css'],
  providers: [ProductTagService, ProductService, ProductCategoryService]
})
export class ProductTagItemCreateComponent extends AbstractModalComponent<ProductTagMeta> {

  list = [];

  constructor(
    service: ProductTagService,
    modal: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService,
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
      search: new FormControl(null),
      tag_id_add: new FormControl(null, Validators.required),
      category_id: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', []),
    ];
  }

  loaded(): void {
    this.formGroup.controls['tag_id_add'].setValue(this.model.id);
    let param: any = ObjectUtil.combineValue({}, this.formGroup.value, true);
    (<ProductService>this.productService).loadTag(param).subscribe((res: any) => {
      this.list = res;
    }, () => {
      this.list = [];
    });
  }

  loadAllCategory() {
    return this.categoryService.loadByParams({child: 0}).subscribe(value => {
      this.fields[1].data = value;
    });
  }

  assignAll() {
    let ids: number[] = this.list.map(v => v.id);
    (<ProductTagService>this.service).attachTags(this.model.id, ids).subscribe((res: ProductMeta) => {
      this.service.toastSuccessfully('Thêm');
      this.close(ObjectUtil.mergeValue(this.model, res));
    }, () => this.service.toastFailed('Thêm'));
  }

  assign(item: ProductMeta) {
    (<ProductTagService>this.service).attachTags(this.model.id, [item.id]).subscribe((res: ProductMeta) => {
      this.service.toastSuccessfully('Thêm');
      this.loaded();
    }, () => this.service.toastFailed('Thêm'));
  }

  dismiss() {
    this.modal.hide();
    this.onHidden.emit(new ModalResult<any>('success'));
  }

  removeFilter() {
    this.formGroup.reset();
    this.loaded();
  }
}
