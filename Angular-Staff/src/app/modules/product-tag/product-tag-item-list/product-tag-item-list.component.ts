import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {ProductTagService} from '../product-tag.service';
import {AppPagination, FieldForm, ModalResult} from '../../../core/common';
import {ProductTagItemCreateComponent} from '../product-tag-item-create/product-tag-item-create.component';
import {ProductTagMeta} from '../product-tag.meta';
import {ProductService} from '../../product/product.service';
import { ObjectUtil } from '../../../core';

@Component({
  selector: 'app-product-tag-item-list',
  templateUrl: './product-tag-item-list.component.html',
  styleUrls: ['./product-tag-item-list.component.css'],
  providers: [ProductTagService, ProductService]
})
export class ProductTagItemListComponent extends AbstractCRUDModalComponent<ProductTagMeta> {

  constructor(
    service: ProductTagService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
    private productService: ProductService
  ) {
    super(service, modalRef, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý gán tag';
  }

  getCreateModalComponent() {
    return ProductTagItemCreateComponent;
  }

  getEditModalComponent() {
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
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
    ];
  }

  initNewModel(): ProductTagMeta {
    const model = new ProductTagMeta();
    model.id = this.relatedModel.id;
    return model;
  }

  loaded(): void {
  }

  load(): void {
    let param: any = ObjectUtil.combineValue({
      tag_id: this.relatedModel.id,
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.productService.loadTag(param).subscribe((res: any) => {
      this.nextPage = this.pagination.currentPage;
      this.list = res.data;
      this.pagination.set(res);
    }, () => {
      this.list = [];
      this.pagination = new AppPagination();
      this.nextPage = this.pagination.currentPage;
    });
  }

  createTag() {
    let modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<ProductTagMeta> = <AbstractModalComponent<ProductTagMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductTagMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  detach(item) {
    (<ProductTagService>this.service).detachTags(this.relatedModel.id, item).subscribe((res: ProductTagMeta) => {
      this.service.toastSuccessfully('Xóa');
      this.load();
    }, () => this.service.toastFailed('Xóa'));
  }

}
