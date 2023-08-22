import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StaffMeta} from '../staff.meta';
import {FieldForm} from '../../../core/common';
import {StaffService} from '../staff.service';

@Component({
  selector: 'app-staff-edit',
  templateUrl: './staff-edit.component.html',
  styleUrls: ['./staff-edit.component.css'],
  providers: [StaffService]
})
export class StaffEditComponent extends AbstractModalComponent<StaffMeta> {

  constructor(
    service: StaffService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      username: new FormControl({value: null, disabled: true}, [Validators.required, Validators.maxLength(255), Validators.pattern('^(?=.*[a-z]+)[a-z0-9]*$')]),
      fullname: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      phone: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('^(0)[0-9]{9}$')]),
      dob: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      gender: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
      address: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      wage: new FormControl(null, [Validators.required, Validators.min(0)]),
      position: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
      bank_name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      bank_number: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên đăng nhập', 'username', 'Nhập tên đăng nhập'),
      FieldForm.createTextInput('Tên nhân viên', 'fullname', 'Nhập tên nhân viên'),
      FieldForm.createNumberInput('Số điện thoại', 'phone', 'Nhập số điện thoại'),
      FieldForm.createDateInput('Ngày sinh', 'dob', 'Chọn ngày'),
      FieldForm.createSelect('Giới tính', 'gender', 'Chọn một', [
        {
          name: 'Nam',
          value: '0'
        },
        {
          name: 'Nữ',
          value: '1'
        }
      ]),
      FieldForm.createTextInput('Địa chỉ', 'address', 'Nhập địa chỉ'),
      FieldForm.createNumberInput('Lương', 'wage', 'Nhập số lương'),
      FieldForm.createSelect('Chức vụ', 'position', 'Chọn một', [
        {
          name: 'Quản lý',
          value: 'Quản lý'
        },
        {
          name: 'Nhân viên',
          value: 'Nhân viên'
        },
        {
          name: 'Nhân viên thời vụ',
          value: 'Nhân viên thời vụ'
        }
      ]),
      FieldForm.createTextInput('Tên ngân hàng', 'bank_name', 'Nhập tên ngân hàng'),
      FieldForm.createTextInput('Số tài khoản', 'bank_number', 'Nhập số tài khoản')
    ];
  }

  loadAllCategories() {
    return this.service.loadAll();
  }

  loaded(): void {
  }
}
