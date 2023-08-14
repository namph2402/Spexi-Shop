import { Component } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { VoucherMeta } from '../voucher.meta';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { VoucherService } from '../voucher.service';
import { FieldForm } from '../../../core/common';

@Component({
  selector: 'app-voucher-create',
  templateUrl: './voucher-create.component.html',
  styleUrls: ['./voucher-create.component.css'],
  providers: [VoucherService]
})
export class VoucherCreateComponent extends AbstractModalComponent<VoucherMeta> {

  check: number;

  constructor(
    service: VoucherService,
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
      code: new FormControl(null, [Validators.required, Validators.maxLength(30), Validators.pattern('^(?=.*[A-Z0-9]+)[A-Z0-9]*$')]),
      type: new FormControl(null, Validators.required),
      quantity: new FormControl(null, [Validators.required, Validators.min(1), Validators.maxLength(10), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
      min_order_value: new FormControl(null, [Validators.required, Validators.min(1), Validators.maxLength(10), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
      expired_date: new FormControl(null, Validators.required),
      private: new FormControl(0),
      status: new FormControl(0),
    }
    );
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên mã giảm', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Mã giảm giá', 'code', 'Nhập mã'),
      FieldForm.createSelect('Loại voucher', 'type', 'Chọn một', [
        {
          id: 1,
          name: 'Giảm giá đơn hàng',
        }, {
          id: 2,
          name: 'Free ship',
        }
      ]),
      FieldForm.createNumberInput('Số lượng', 'quantity', 'Nhập giá trị'),
      FieldForm.createNumberInput('Giá trị đơn hàng tối thiểu', 'min_order_value', 'Nhập giá trị',),
      FieldForm.createDateInput('Ngày hết hạn', 'expired_date', 'Nhập Ngày hết hạn'),
      FieldForm.createCheckbox('Trạng thái', 'status', 'Trạng thái'),
      FieldForm.createCheckbox('Riêng tư', 'private', 'Riêng tư'),
    ];
  }

  loaded(): void {
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['type'].valueChanges.subscribe(value => {
      if (this.fields.length > 8) {
        this.fields.splice(8);
      } if (value) {
        if (value == 1) {
          let fileForm = FieldForm.createSelect('Loại giá trị', 'value', 'Chọn một', [
            {
              id: 1,
              name: 'Giảm giá sản phẩm',
            }, {
              id: 2,
              name: 'Chiết khấu sản phẩm',
            }]
          );
          this.formGroup.addControl('value', new FormControl(1));
          this.fields.push(fileForm);
          let fileForm1 = FieldForm.createNumberInput('Giảm giá sản phẩm', 'discount_value', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
        }
      }
      if (value == 2) {
        if (this.formGroup.controls['value']) {
          this.formGroup.removeControl('value');
        }
        if (this.formGroup.controls['discount_percent']) {
          this.formGroup.removeControl('discount_percent');
        }
        if (this.formGroup.controls['discount_value']) {
          this.formGroup.removeControl('discount_value');
        }
      }
    });
  }

  changeInput() {
    if (this.fields.length > 9) {
      this.fields.splice(9);
    }
    if (this.formGroup.get('value').value == 2) {
      if (this.formGroup.controls['discount_percent']) {
        this.formGroup.removeControl('discount_percent');
      }
      let fileForm1 = FieldForm.createNumberInput('Giảm giá sản phẩm', 'discount_value', 'Nhập giá trị');
      this.fields.push(fileForm1);
      this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
    }
    if (this.formGroup.get('value').value == 1) {
      if (this.formGroup.controls['discount_value']) {
        this.formGroup.removeControl('discount_value');
      }
      let fileForm2 = FieldForm.createNumberInput('Chiết khấu sản phẩm', 'discount_percent', 'Nhập giá trị');
      this.fields.push(fileForm2);
      this.formGroup.addControl('discount_percent', new FormControl(null, [Validators.required, Validators.min(1), Validators.max(100), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
    }
  }
}
