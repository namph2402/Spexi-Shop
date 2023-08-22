import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AppPagination, ModalResult} from '../../../core/common';
import {ProductImageService} from '../product-image.service';
import {ProductImageMeta} from '../product-image.meta';
import {ProductImageCreateComponent} from '../product-image-create/product-image-create.component';

@Component({
  selector: 'app-product-image-list',
  templateUrl: './product-image-list.component.html',
  styleUrls: ['./product-image-list.component.css'],
  providers: [ProductImageService]
})
export class ProductImageListComponent extends AbstractCRUDModalComponent<ProductImageMeta> {

  constructor(
    service: ProductImageService,
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
    return ProductImageCreateComponent;
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
    return this.formBuilder.group({});
  }

  initNewModel(): ProductImageMeta {
    const model = new ProductImageMeta();
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

  onStatusChange(item: ProductImageMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: ProductImageMeta) => {
      this.list[index].status = res.status;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  upOrder(item: ProductImageMeta) {
    (<ProductImageService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: ProductImageMeta) {
    (<ProductImageService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }

  createImage() {
    let modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<ProductImageMeta> = <AbstractModalComponent<ProductImageMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductImageMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

}
