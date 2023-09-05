import { Component } from "@angular/core";
import { ProfileService } from "./profile.service";
import { AbstractCRUDComponent, FieldForm, ObjectUtil, StorageUtil } from "../../core";
import { ProfileMeta } from "./profile.meta";
import { BsModalService, ModalOptions } from "ngx-bootstrap";
import { FormBuilder, FormControl, FormGroup, Validators } from "@angular/forms";

@Component({
  selector: 'app-profile',
  templateUrl: 'profile.component.html',
  styleUrls: ['profile.component.css'],
  providers: [ProfileService]
})
export class ProfileComponent extends AbstractCRUDComponent<ProfileMeta> {

  constructor(
    service: ProfileService,
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
    return 'Đổi mật khẩu';
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

  load(): void {
    return null;
  }

  initNewModel(): ProfileMeta {
    return new ProfileMeta();
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      uername: new FormControl({value: StorageUtil.get('username'), disabled: true}),
      name: new FormControl({value: StorageUtil.get('name'), disabled: true}),
      password: new FormControl(null, [Validators.required, Validators.minLength(6), Validators.pattern('[^ ].*$')]),
      newPassword: new FormControl(null, [Validators.required, Validators.minLength(6), Validators.pattern('[^ ].*$')]),
      rePassword: new FormControl(null, [Validators.required, Validators.minLength(6), Validators.pattern('[^ ].*$')]),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Họ tên', 'name', 'Họ tên'),
      FieldForm.createTextInput('Tên đăng nhập', 'uername', 'Tên đăng nhập'),
      FieldForm.createPasswordInput('Mật khẩu cũ', 'password', 'Nhập mật khẩu'),
      FieldForm.createPasswordInput('Mật khẩu mới', 'newPassword', 'Nhập mật khẩu mới'),
      FieldForm.createPasswordInput('Nhập lại mật khẩu', 'rePassword', 'Nhập lại mật khẩu'),
    ];
  }

  updatePassword() {
    let data: any = ObjectUtil.combineValue({}, this.searchForm.value, true);
    console.log(data);
    if(data.newPassword != data.rePassword) {
      this.service.toastError("Mật khẩu không khớp");
    } else {
      this.service.update(data).subscribe((res: ProfileMeta) => {
        this.service.toastSuccess('Đổi mật khẩu thành công');
        this.reset();
      }, () => this.service.toastFailed('Đổi mật khẩu thất bại'));
    }
  }

  reset() {
    this.searchForm.get('password').setValue(null);
    this.searchForm.get('newPassword').setValue(null);
    this.searchForm.get('rePassword').setValue(null);
  }

}
