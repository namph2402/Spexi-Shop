import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PromotionMeta} from '../promotion.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PromotionService} from '../promotion.service';
import {FieldForm} from '../../../core/common';
import {ProductService} from '../../product/product.service';

@Component({
  selector: 'app-promotion-create',
  templateUrl: './promotion-create.component.html',
  styleUrls: ['./promotion-create.component.css'],
  providers: [PromotionService, ProductService]
})
export class PromotionCreateComponent extends AbstractModalComponent<PromotionMeta> {

  constructor(
    service: PromotionService,
    modal: BsModalRef,
    builder: FormBuilder,
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
      type: new FormControl(null, Validators.required),
      expired_date: new FormControl(null, Validators.required),
      image: new FormControl(null),
      status: new FormControl(0),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createSelect('Loại chương trình', 'type', 'Chọn một', [
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
      FieldForm.createDateInput('Thời gian hết hạn', 'expired_date', 'Chọn ngày hết hạn'),
      FieldForm.createFileInput('Ảnh banner', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
      FieldForm.createCheckbox('Kích hoạt', 'status', 'Chọn'),
    ];
  }

  loaded(): void {
  }

  onFormChanged() {
    super.onFormChanged();
    this.formGroup.controls['type'].valueChanges.subscribe(value => {
      if (this.fields.length > 5) {
        this.fields.splice(5);
      }
      if (value) {
        if (value == 1) {
          if (this.formGroup.controls['discount_same']) {
            this.formGroup.removeControl('discount_same');
          }
          if (this.formGroup.controls['min_order_value']) {
            this.formGroup.removeControl('min_order_value');
          }
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
        if (value == 2) {
          if (this.formGroup.controls['discount_percent']) {
            this.formGroup.removeControl('discount_percent');
          }
          if (this.formGroup.controls['discount_value']) {
            this.formGroup.removeControl('discount_value');
          }
          if (this.formGroup.controls['min_order_value']) {
            this.formGroup.removeControl('min_order_value');
          }
          let fileForm = FieldForm.createNumberInput('Đồng giá', 'discount_same', 'Nhập giá trị');
          this.fields.push(fileForm);
          this.formGroup.addControl('discount_same', new FormControl(null, [Validators.required, Validators.min(0), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
        }
        if (value == 3) {
          if (this.formGroup.controls['discount_percent']) {
            this.formGroup.removeControl('discount_percent');
          }
          if (this.formGroup.controls['discount_value']) {
            this.formGroup.removeControl('discount_value');
          }
          if (this.formGroup.controls['discount_same']) {
            this.formGroup.removeControl('discount_same');
          }
          let fileForm1 = FieldForm.createNumberInput('Giá trị đơn hàng tối thiểu', 'min_order_value', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
        }
        if (value == 4) {
          if (this.formGroup.controls['discount_percent']) {
            this.formGroup.removeControl('discount_percent');
          }
          if (this.formGroup.controls['discount_same']) {
            this.formGroup.removeControl('min_order_value');
          }
          let fileForm = FieldForm.createNumberInput('Giá trị đơn hàng tối thiểu', 'min_order_value', 'Nhập giá trị');
          this.fields.push(fileForm);
          this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
          let fileForm1 = FieldForm.createNumberInput('Giảm giá đơn hàng', 'discount_value', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]));
        }
      } else {
        this.fields.splice(5);
      }
    });
  }

  changeInput() {
    if (this.fields.length > 6) {
      this.fields.splice(6);
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
