import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {ImportService} from '../import.service';
import {ImportMeta} from '../import.meta';

@Component({
  selector: 'app-import-detail-list',
  templateUrl: './import-detail-list.component.html',
  styleUrls: ['./import-detail-list.component.css'],
  providers: [ImportService]
})
export class ImportDetailListComponent extends AbstractCRUDModalComponent<ImportMeta> {

  constructor(
    service: ImportService,
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
    return null;
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

  initNewModel(): ImportMeta {
    return new ImportMeta();
  }

  loaded(): void {
  }

  load(): void {
    this.list = this.relatedModel.details;
  }

}
