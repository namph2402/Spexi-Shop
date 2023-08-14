import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import {VariantService} from '../variant.service';
import {VariantMeta} from '../variant.meta';
import {VariantCreateComponent} from '../variant-create/variant-create.component';
import {VariantEditComponent} from '../variant-edit/variant-edit.component';
import {ProductSizeService} from '../../product-size/product-size.service';
import {ProductColorService} from '../../product-color/product-color.service';

@Component({
  selector: 'app-variant',
  templateUrl: './variant-list.component.html',
  styleUrls: ['./variant-list.component.css'],
  providers: [VariantService, ProductSizeService, ProductColorService]
})
export class VariantListComponent extends AbstractCRUDComponent<VariantMeta> {

  listSize: any;
  listColor: any;

  constructor(
    service: VariantService,
    modal: BsModalService,
    builder: FormBuilder,
    protected sizeService: ProductSizeService,
    protected colorService: ProductColorService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý biến thể';
  }

  getCreateModalComponent(): any {
    return VariantCreateComponent;
  }

  getEditModalComponent(): any {
    return VariantEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.pattern('[^ ].*$')),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
    ];
  }

  initNewModel(): VariantMeta {
    return new VariantMeta();
  }

  load(): void {
    const params = ObjectUtil.ignoreNullValue(this.searchForm.value);
    this.sizeService.loadByParams(params).subscribe((data: any) => {
      this.listSize = data;
    });
    this.colorService.loadByParams(params).subscribe((data: any) => {
      this.listColor = data;
    });
  }

  createVariant() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<VariantMeta> = <AbstractModalComponent<VariantMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<VariantMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editVariantSize(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<VariantMeta> = <AbstractModalComponent<VariantMeta>>modalRef.content;
    item.type = 0;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<VariantMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editVariantColor(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<VariantMeta> = <AbstractModalComponent<VariantMeta>>modalRef.content;
    item.type = 1;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<VariantMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  removeVariantColor(item) {
    this.colorService.destroy(item['id']).subscribe(() => {
      this.service.toastSuccessfullyDeleted();
      this.load();
    }, () => this.service.toastFailedDeleted());
  }

  removeVariantSize(item) {
    this.sizeService.destroy(item['id']).subscribe(() => {
      this.service.toastSuccessfullyDeleted();
      this.load();
    }, () => this.service.toastFailedDeleted());
  }

}
