import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderShipService} from '../order-ship.service';
import {AbstractModalComponent, FieldForm, ObjectUtil} from '../../../core';
import {OrderShipMeta} from '../order-ship.meta';

@Component({
  selector: 'app-order-ship-note',
  templateUrl: './order-ship-note.component.html',
  styleUrls: ['./order-ship-note.component.css'],
  providers: [OrderShipService]
})
export class OrderShipNoteComponent extends AbstractModalComponent<OrderShipMeta> {

  constructor(
    service: OrderShipService,
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
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự'),
    ];
  }

  loaded(): void {
  }

  note() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    (<OrderShipService>this.service).note(item).subscribe(res => {
      this.service.toastSuccessfully('Ghi chú');
      this.close(res);
    }, () => this.service.toastFailed('Ghi chú'));
  }
}
