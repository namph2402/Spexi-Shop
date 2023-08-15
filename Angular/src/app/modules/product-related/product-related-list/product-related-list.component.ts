import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AppPagination, FieldForm, ModalResult} from '../../../core/common';
import {ProductRelatedService} from '../product-related.service';
import {ProductRelatedMeta} from '../product-related.meta';
import {ProductRelatedCreateComponent} from '../product-related-create/product-related-create.component';
import { ProductCategoryService } from '../../product-category/product-category.service';
import { ObjectUtil } from '../../../core';

@Component({
  selector: 'app-product-related-list',
  templateUrl: './product-related-list.component.html',
  styleUrls: ['./product-related-list.component.css'],
  providers: [ProductRelatedService, ProductCategoryService]
})
export class ProductRelatedListComponent extends AbstractCRUDModalComponent<ProductRelatedMeta> {

  constructor(
    service: ProductRelatedService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
    private categoryService: ProductCategoryService,
  ) {
    super(service, modalRef, modal, builder);
    this.loadAllCategory();
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý sản phẩm liên quan';
  }

  getCreateModalComponent(): any {
    return ProductRelatedCreateComponent;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      category_id: new FormControl(null)
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', '', 'col-md-6'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', [], 'col-md-6'),
    ];
  }

  initNewModel(): ProductRelatedMeta {
    const model = new ProductRelatedMeta();
    model.product_id = this.relatedModel.id;
    return model;
  }

  loadAllCategory() {
    return this.categoryService.loadAll().subscribe(value => {

      this.searchControls[1].data = value;
    });
  }


  loaded(): void {
  }

  load(): void {
    let param: any = ObjectUtil.combineValue({}, this.searchForm.value, true);
    param['product_id'] = this.relatedModel.id,
    param['limit'] = this.pagination.itemsPerPage,
    param['page'] = this.pagination.currentPage,
    this.service.loadByPage(param).subscribe((res: any) => {
      this.nextPage = this.pagination.currentPage;
      this.list = res.data;
      this.pagination.set(res);
    }, () => {
      this.list = [];
      this.pagination = new AppPagination();
      this.nextPage = this.pagination.currentPage;
    });
  }

  upOrder(item: ProductRelatedMeta) {
    (<ProductRelatedService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: ProductRelatedMeta) {
    (<ProductRelatedService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }

  createRelated() {
    let modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<ProductRelatedMeta> = <AbstractModalComponent<ProductRelatedMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductRelatedMeta>) => {
      this.load();
      sub.unsubscribe();
    });
  }

}
