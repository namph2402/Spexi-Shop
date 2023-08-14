import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AppPagination, ModalResult} from '../../../core/common';
import {ProductVariantMeta} from '../product-variant.meta';
import {ProductVariantCreateComponent} from '../product-variant-create/product-variant-create.component';
import {ObjectUtil} from '../../../core/utils';
import {ProductVariantService} from '../product-variant.service';
import {ProductVariantEditComponent} from '../product-variant-edit/product-variant-edit.component';

@Component({
  selector: 'app-product-variant-list',
  templateUrl: './product-variant-list.component.html',
  styleUrls: ['./product-variant-list.component.css'],
  providers: [ProductVariantService]
})
export class ProductVariantListComponent extends AbstractCRUDModalComponent<ProductVariantMeta> {

  constructor(
    service: ProductVariantService,
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
    return 'Quản lý ảnh sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductVariantCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductVariantEditComponent;
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

  initNewModel(): ProductVariantMeta {
    const model = new ProductVariantMeta();
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

  onStatusChange(item: ProductVariantMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: ProductVariantMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createVariant() {
    let modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<ProductVariantMeta> = <AbstractModalComponent<ProductVariantMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductVariantMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editVariant(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductVariantMeta> = <AbstractModalComponent<ProductVariantMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ProductVariantMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

}
