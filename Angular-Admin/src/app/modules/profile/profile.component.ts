import { Component } from "@angular/core";
import { ProfileService } from "./profile.service";
import { AbstractCRUDComponent, FieldForm, ObjectUtil, StorageUtil } from "../../core";
import { ProfileMeta } from "./profile.meta";
import { BsModalService, ModalOptions } from "ngx-bootstrap";
import { FormBuilder, FormControl, FormGroup, Validators } from "@angular/forms";

// @Component({
//   selector: 'app-dashboard',
//   templateUrl: 'profile.component.html',
//   styleUrls: ['profile.component.css'],
//   providers: [ProfileService]
// })
// export class ProfileComponent extends AbstractCRUDComponent<ProfileMeta> {

//   constructor(
//     service: ProfileMeta,
//     modal: BsModalService,
//     builder: FormBuilder,
//   ) {
//     super(service, modal, builder);
//   }

//   public onInit(): void {
//     this.load();
//   }

//   public onDestroy(): void {
//   }

//   public getTitle(): string {
//     return 'Đổi mật khẩu';
//   }

//   public getCreateModalComponent() {
//     throw new Error('Method not implemented.');
//   }
//   public getEditModalComponent() {
//     throw new Error('Method not implemented.');
//   }
//   public getCreateModalComponentOptions(): ModalOptions {
//     throw new Error('Method not implemented.');
//   }
//   public getEditModalComponentOptions(): ModalOptions {
//     throw new Error('Method not implemented.');
//   }
//   public buildSearchForm(): FormGroup {
//     throw new Error('Method not implemented.');
//   }
//   public initNewModel(): ProfileMeta {
//     throw new Error('Method not implemented.');
//   }

//   user: AuthMeta;
//   form: FormGroup;

//   ngOnInit() {
//   }

  // update() {
  //   let data = {
  //     name: this.form.controls['name'].value,
  //     oldpassword: this.form.controls['oldpassword'].value,
  //     password: this.form.controls['password'].value,
  //   };
  //   this.service.update(data).subscribe(() => {
  //     this.toast.pop('success', 'Thay đổi thông tin cá nhân', 'Thành công');
  //     StorageUtil.set('name', data.name);
  //     this.form.controls['password'].setValue(null);
  //     this.form.controls['confirmPassword'].setValue(null);
  //   }, () => {
  //     this.toast.pop('error', 'Thay đổi thông tin cá nhân', 'Thất bại');
  //   });
  // }

  // matchPassword(c: FormGroup) {
  //   if (c.get('password').value !== c.get('confirmPassword').value) {
  //     return {matchPassword: true};
  //   }
  //   return null;
  // }

// }

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
    if(data.newPassword != data.rePassword) {
      this.service.toastError("Mật khẩu không khớp");
    } else {
      this.service.update(data).subscribe((res: ProfileMeta) => {
        this.service.toastSuccess('Đổi mật khẩu thành công');
        this.searchForm.reset();
      }, () => this.service.toastFailed('Đổi mật khẩu thất bại'));
    }
  }

}
