import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import {ProductTagService} from '../product-tag.service';
import {ProductTagMeta} from '../product-tag.meta';
import {ProductTagCreateComponent} from '../product-tag-create/product-tag-create.component';
import {ProductTagEditComponent} from '../product-tag-edit/product-tag-edit.component';
import { ProductTagItemListComponent } from '../product-tag-item-list/product-tag-item-list.component';

@Component({
  selector: 'app-product-tag',
  templateUrl: './product-tag-list.component.html',
  styleUrls: ['./product-tag-list.component.css'],
  providers: [ProductTagService]
})
export class ProductTagListComponent extends AbstractCRUDComponent<ProductTagMeta> {

  constructor(
    service: ProductTagService,
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
    return 'Quản lý tag sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductTagCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductTagEditComponent;
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
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
    ];
  }

  initNewModel(): ProductTagMeta {
    return new ProductTagMeta();
  }

  createProductTag() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ProductTagMeta> = <AbstractModalComponent<ProductTagMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<ProductTagMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editProductTag(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductTagMeta> = <AbstractModalComponent<ProductTagMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ProductTagMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  showProduct(item: ProductTagMeta) {
    let modalRef = this.modalService.show(ProductTagItemListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ProductTagMeta> = <AbstractCRUDModalComponent<ProductTagMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductTagMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  upOrder(item: ProductTagMeta) {
    (<ProductTagService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: ProductTagMeta) {
    (<ProductTagService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }
}
