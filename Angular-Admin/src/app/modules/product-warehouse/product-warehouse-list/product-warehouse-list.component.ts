import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AppPagination, ModalResult} from '../../../core/common';
import {ProductWarehouseMeta} from '../product-warehouse.meta';
import {ProductWarehouseCreateComponent} from '../product-warehouse-create/product-warehouse-create.component';
import {ObjectUtil} from '../../../core/utils';
import {ProductWarehouseService} from '../product-warehouse.service';
import {ProductWarehouseEditComponent} from '../product-warehouse-edit/product-warehouse-edit.component';

@Component({
  selector: 'app-product-warehouse-list',
  templateUrl: './product-warehouse-list.component.html',
  styleUrls: ['./product-warehouse-list.component.css'],
  providers: [ProductWarehouseService]
})
export class ProductWarehouseListComponent extends AbstractCRUDModalComponent<ProductWarehouseMeta> {

  constructor(
    service: ProductWarehouseService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý kho sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductWarehouseCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductWarehouseEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  initNewModel(): ProductWarehouseMeta {
    const model = new ProductWarehouseMeta();
    model.product_id = this.relatedModel.id;
    return model;
  }

  loaded(): void {
  }

  load(): void {
    let param = {
      product_id: this.relatedModel.id,
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    };
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

  onStatusChange(item: ProductWarehouseMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: ProductWarehouseMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createWarehouse() {
    let modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<ProductWarehouseMeta> = <AbstractModalComponent<ProductWarehouseMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductWarehouseMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editWarehouse(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductWarehouseMeta> = <AbstractModalComponent<ProductWarehouseMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ProductWarehouseMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

}
