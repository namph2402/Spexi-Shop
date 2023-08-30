import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ImportMeta} from '../import.meta';
import {ImportService} from '../import.service';
import {FieldForm, ModalResult} from '../../../core/common';
import { ImportDetailListComponent } from '../import-detail-list/import-detail-list.component';
import { ImportDetailMeta } from '../import-detail.meta';

@Component({
  selector: 'app-import',
  templateUrl: './import-list.component.html',
  styleUrls: ['./import-list.component.css'],
  providers: [ImportService]
})
export class ImportListComponent extends AbstractCRUDComponent<ImportMeta> {

  constructor(
    service: ImportService,
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
    return 'Phiếu nhập kho';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
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
      date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createDateInput('Tìm kiếm ngày nhập', 'date', 'Chọn một')
    ];
  }

  initNewModel(): ImportMeta {
    return new ImportMeta();
  }

  showDetail(item: ImportMeta) {
    let modalRef = this.modalService.show(ImportDetailListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ImportDetailMeta> = <AbstractCRUDModalComponent<ImportDetailMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ImportDetailMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

}
