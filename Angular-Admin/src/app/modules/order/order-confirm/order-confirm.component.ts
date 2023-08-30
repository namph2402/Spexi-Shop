import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {AbstractModalComponent, FieldForm, ObjectUtil} from '../../../core';

@Component({
  selector: 'app-order-confirm',
  templateUrl: './order-confirm.component.html',
  styleUrls: ['./order-confirm.component.css'],
  providers: [OrderService]
})
export class OrderConfirmComponent extends AbstractModalComponent<OrderMeta> {

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
      FieldForm.createTextArea('Ghi chú xác nhận', 'note', 'Nhập kí tự'),
    ];
  }

  loaded(): void {
  }

  confirm() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    (<OrderService>this.service).confirm(item).subscribe(res => {
      this.service.toastSuccessfully('Hủy');
      this.close(res);
    }, () => this.service.toastFailed('Hủy'));
  }
}
