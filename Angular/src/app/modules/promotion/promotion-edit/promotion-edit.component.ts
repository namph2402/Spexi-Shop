import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PromotionMeta} from '../promotion.meta';
import {PromotionService} from '../promotion.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-promotion-edit',
  templateUrl: './promotion-edit.component.html',
  styleUrls: ['./promotion-edit.component.css'],
  providers: [PromotionService]
})
export class PromotionEditComponent extends AbstractModalComponent<PromotionMeta> {

  constructor(
    service: PromotionService,
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
      name: new FormControl(null, Validators.required),
      type: new FormControl({value: null, disabled: true}, Validators.required),
      expired_date: new FormControl(null, Validators.required),
      image: new FormControl(null),
      status: new FormControl(0),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createSelect('Loại chương trình', 'type', '', [
        {
          id: 1,
          name: 'Giảm giá sản phẩm',
        }, {
          id: 2,
          name: 'Đồng giá',
        }, {
          id: 3,
          name: 'FreeShip',
        }, {
          id: 4,
          name: 'Giảm giá đơn hàng',
        }
      ]),
      FieldForm.createDateTimeInput('Thời gian hết hạn', 'expired_date', 'Chọn ngày hết hạn'),
      FieldForm.createFileInput('Ảnh banner', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
      FieldForm.createCheckbox('Kích hoạt', 'status', 'Chọn'),
    ];
  }

  loaded(): void {
    if (this.model.type == 1) {
      if (this.model.discount_percent > 0) {
        let fileForm1 = FieldForm.createNumberInput('Chiết khấu sản phẩm', 'discount_percent', 'Nhập giá trị');
        this.fields.push(fileForm1);
        this.formGroup.addControl('discount_percent', new FormControl([Validators.required, Validators.min(1), Validators.max(100), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
      }
      if (this.model.discount_value) {
        let fileForm = FieldForm.createNumberInput('Giảm giá sản phẩm', 'discount_value', 'Nhập giá trị');
        this.fields.push(fileForm);
        this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
      }
    }
    if (this.model.type == 2) {
      let fileForm = FieldForm.createNumberInput('Đồng giá', 'discount_same', 'Nhập giá trị');
      this.formGroup.addControl('discount_same', new FormControl(null, [Validators.required, Validators.min(0)]));
      this.fields.push(fileForm);
    }
    if (this.model.type == 3) {
      let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
      this.fields.push(fileForm1);
      this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
    }
    if (this.model.type == 4) {
      let fileForm = FieldForm.createNumberInput('Chiết khấu theo đơn hàng', 'discount_value', 'Nhập giá trị');
      this.fields.push(fileForm);
      this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
      let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
      this.fields.push(fileForm1);
      this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
    }
  }

}
