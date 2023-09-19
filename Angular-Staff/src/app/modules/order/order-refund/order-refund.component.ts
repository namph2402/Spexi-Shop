import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {AbstractModalComponent, FieldForm, ObjectUtil} from '../../../core';

@Component({
  selector: 'app-order-refund',
  templateUrl: './order-refund.component.html',
  styleUrls: ['./order-refund.component.css'],
  providers: [OrderService]
})
export class OrderRefundComponent extends AbstractModalComponent<OrderMeta> {

  constructor(
    service: OrderService,
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
      customer_namez: new FormControl({value: null, disabled: true}, Validators.required),
      total_amount: new FormControl(null, Validators.required),
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Khách hàng', 'customer_namez', 'Nhập tên'),
      FieldForm.createNumberInput('Số tiền hoàn', 'total_amount', 'Nhập giá'),
      FieldForm.createTextArea('Lý do hủy đơn', 'note', 'Nhập kí tự'),
    ];
  }

  loaded(): void {
    let text =this.model.customer_name+" - "+this.model.customer_phone;
    this.formGroup.controls['customer_namez'].setValue(text);
  }

  refund() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    (<OrderService>this.service).refund(item).subscribe(res => {
      this.service.toastSuccessfully('Hoàn tiền');
      this.close(res);
    }, () => this.service.toastFailed('Hoàn tiền'));
  }
}
