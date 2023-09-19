import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {AbstractModalComponent, FieldForm, ObjectUtil} from '../../../core';

@Component({
  selector: 'app-order-cancel',
  templateUrl: './order-cancel.component.html',
  styleUrls: ['./order-cancel.component.css'],
  providers: [OrderService]
})
export class OrderCancelComponent extends AbstractModalComponent<OrderMeta> {

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
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Lý do hủy đơn', 'note', 'Nhập kí tự'),
    ];
  }

  loaded(): void {
  }

  cancel() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    (<OrderService>this.service).cancel(item).subscribe(res => {
      this.service.toastSuccessfully('Hủy');
      this.close(res);
    }, () => this.service.toastFailed('Hủy'));
  }
}
