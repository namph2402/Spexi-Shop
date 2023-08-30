import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {NotificationMeta} from '../notification.meta';
import {NotificationService} from '../notification.service';
import {NotificationCreateComponent} from '../notification-create/notification-create.component';
import {NotificationEditComponent} from '../notification-edit/notification-edit.component';
import {FieldForm, ModalResult, ObjectUtil} from '../../../core';

@Component({
  selector: 'app-notification',
  templateUrl: './notification-list.component.html',
  styleUrls: ['./notification-list.component.css'],
  providers: [NotificationService]
})
export class NotificationListComponent extends AbstractCRUDComponent<NotificationMeta> {

  constructor(
    service: NotificationService,
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
    return 'Quản lý khách hàng';
  }

  getCreateModalComponent(): any {
    return NotificationCreateComponent;
  }

  getEditModalComponent(): any {
    return NotificationEditComponent;
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

  initNewModel(): NotificationMeta {
    return new NotificationMeta();
  }

  onStatusChange(item: NotificationMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: NotificationMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createNotification() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<NotificationMeta> = <AbstractModalComponent<NotificationMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<NotificationMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editNotification(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<NotificationMeta> = <AbstractModalComponent<NotificationMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<NotificationMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }
}
