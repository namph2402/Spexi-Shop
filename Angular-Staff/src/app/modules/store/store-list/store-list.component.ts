import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {StoreMeta} from '../store.meta';
import {StoreService} from '../store.service';
import {FieldForm} from '../../../core';

@Component({
  selector: 'app-store',
  templateUrl: './store-list.component.html',
  styleUrls: ['./store-list.component.css'],
  providers: [StoreService]
})
export class StoreListComponent extends AbstractCRUDComponent<StoreMeta> {

  constructor(
    service: StoreService,
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
    return 'Quản lý thông tin cửa hàng';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
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

  initNewModel(): StoreMeta {
    return new StoreMeta();
  }

}
