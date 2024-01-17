import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {VoucherMeta} from '../voucher.meta';
import {VoucherService} from '../voucher.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-voucher-edit',
  templateUrl: './voucher-edit.component.html',
  styleUrls: ['./voucher-edit.component.css'],
  providers: [VoucherService]
})
export class VoucherEditComponent extends AbstractModalComponent<VoucherMeta> {

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
      type: new FormControl({value: null, disabled: true}, Validators.required),
      quantity: new FormControl(null, [Validators.required, Validators.min(1), Validators.maxLength(10), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
      min_order_value: new FormControl(null, [Validators.required, Validators.min(1), Validators.maxLength(10), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
      expired_date: new FormControl(null, Validators.required),
      private: new FormControl(0),
      status: new FormControl(0),
    });
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
      FieldForm.createNumberInput('Số lượng', 'quantity', 'Nhập số lượng'),
      FieldForm.createNumberInput('Giá trị đơn hàng tối thiểu', 'min_order_value', 'Nhập giá trị'),
      FieldForm.createDateInput('Ngày hết hạn', 'expired_date', 'Nhập giá trị'),
      FieldForm.createCheckbox('Trạng thái', 'status', 'Trạng thái'),
      FieldForm.createCheckbox('Riêng tư', 'private', 'Riêng tư'),
    ];
  }

  loaded(): void {
    if (this.model.type == 1) {
      if (this.model.discount_percent > 0) {
        let fileForm1 = FieldForm.createNumberInput('Chiết khấu sản phẩm', 'discount_percent', 'Nhập giá trị');
        this.formGroup.addControl('discount_percent', new FormControl(null, [Validators.required, Validators.min(1), Validators.max(100), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
        this.fields.push(fileForm1);
      }
      if (this.model.discount_value > 0) {
        let fileForm = FieldForm.createNumberInput('Giảm giá sản phẩm', 'discount_value', 'Nhập giá trị');
        this.fields.push(fileForm);
        this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
      }
    }
  }

}
