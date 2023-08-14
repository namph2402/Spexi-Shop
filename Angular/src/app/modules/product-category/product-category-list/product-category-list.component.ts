import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import {ProductCategoryService} from '../product-category.service';
import {ProductCategoryMeta} from '../product-category.meta';
import {ProductCategoryCreateComponent} from '../product-category-create/product-category-create.component';
import {ProductCategoryEditComponent} from '../product-category-edit/product-category-edit.component';

@Component({
  selector: 'app-product-category',
  templateUrl: './product-category-list.component.html',
  styleUrls: ['./product-category-list.component.css'],
  providers: [ProductCategoryService]
})
export class ProductCategoryListComponent extends AbstractCRUDComponent<ProductCategoryMeta> {

  constructor(
    service: ProductCategoryService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý danh mục sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductCategoryCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductCategoryEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
    ];
  }

  initNewModel(): ProductCategoryMeta {
    return new ProductCategoryMeta();
  }

  createProductCategory() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ProductCategoryMeta> = <AbstractModalComponent<ProductCategoryMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<ProductCategoryMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editProductCategory(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductCategoryMeta> = <AbstractModalComponent<ProductCategoryMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ProductCategoryMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  upOrder(item: ProductCategoryMeta) {
    (<ProductCategoryService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: ProductCategoryMeta) {
    (<ProductCategoryService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }
}
