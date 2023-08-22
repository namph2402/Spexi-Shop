import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {StoreMeta} from '../store.meta';
import {StoreService} from '../store.service';
import {StoreCreateComponent} from '../store-create/store-create.component';
import {StoreEditComponent} from '../store-edit/store-edit.component';
import {FieldForm, ModalResult, ObjectUtil} from '../../../core';

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
    return StoreCreateComponent;
  }

  getEditModalComponent(): any {
    return StoreEditComponent;
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
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
    ];
  }

  initNewModel(): StoreMeta {
    return new StoreMeta();
  }

  createStore() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<StoreMeta> = <AbstractModalComponent<StoreMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<StoreMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editStore(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<StoreMeta> = <AbstractModalComponent<StoreMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<StoreMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }
}
