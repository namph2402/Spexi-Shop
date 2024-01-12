import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {StaffMeta} from '../staff.meta';
import {StaffService} from '../staff.service';
import {StaffCreateComponent} from '../staff-create/staff-create.component';
import {StaffEditComponent} from '../staff-edit/staff-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';

@Component({
  selector: 'app-staff',
  templateUrl: './staff-list.component.html',
  styleUrls: ['./staff-list.component.css'],
  providers: [StaffService]
})
export class StaffListComponent extends AbstractCRUDComponent<StaffMeta> {

  constructor(
    service: StaffService,
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
    return 'Quản lý nhân viên';
  }

  getCreateModalComponent(): any {
    return StaffCreateComponent;
  }

  getEditModalComponent(): any {
    return StaffEditComponent;
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
      position: new FormControl(null),
      status: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm chức vụ', 'position', 'Chọn một', [
        {
          name:'Nhân viên',
          value: 'Nhân viên'
        },
        {
          name:'Nhân viên thời vụ',
          value: 'Nhân viên thời vụ'
        },
      ]),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          name:'Hoạt động',
          value: '1'
        },
        {
          name:'Không hoạt động',
          value: '0'
        },
      ]),
    ];
  }

  initNewModel(): StaffMeta {
    return new StaffMeta();
  }

  onStatusChange(item: StaffMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Mở kích hoạt';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Khóa kích hoạt';
    }
    methodAsync.subscribe((res: StaffMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createStaff() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<StaffMeta> = <AbstractModalComponent<StaffMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<StaffMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editStaff(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<StaffMeta> = <AbstractModalComponent<StaffMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<StaffMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  repassword(item) {
    (<StaffService>this.service).repassword(item.id).subscribe(res => {
      this.service.toastSuccessfully('Cập nhật mật khẩu');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
}
