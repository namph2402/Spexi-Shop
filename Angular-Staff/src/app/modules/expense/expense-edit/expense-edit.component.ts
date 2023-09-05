import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ExpenseMeta} from '../expense.meta';
import {ExpenseService} from '../expense.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-expense-edit',
  templateUrl: './expense-edit.component.html',
  styleUrls: ['./expense-edit.component.css'],
  providers: [ExpenseService]
})
export class ExpenseEditComponent extends AbstractModalComponent<ExpenseMeta> {

  constructor(
    service: ExpenseService,
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
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      description: new FormControl(null),
      amount: new FormControl(null, [Validators.required, Validators.min(0), Validators.maxLength(20), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
      date_created: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên chi phí', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Mô tả', 'description', 'Nhập mô tả'),
      FieldForm.createNumberInput('Số tiền', 'amount', 'Nhập số tiền'),
      FieldForm.createDateTimeInput('Ngày chi tiêu', 'date_created', 'Chọn một'),
    ];
  }

  loadAllCategories() {
    return this.service.loadAll();
  }

  loaded(): void {
  }
}
