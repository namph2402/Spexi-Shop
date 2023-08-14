import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {FormDataMeta} from '../form-data.meta';
import {FormDataService} from '../form-data.service';

@Component({
  selector: 'app-form-data',
  templateUrl: './form-data-list.component.html',
  styleUrls: ['./form-data-list.component.css'],
  providers: [FormDataService]
})
export class FormDataListComponent extends AbstractCRUDComponent<FormDataMeta> {

  constructor(
    service: FormDataService,
    modal: BsModalService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý dữ liệu';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
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

  public initNewModel(): FormDataMeta {
    return new FormDataMeta();
  }

}
