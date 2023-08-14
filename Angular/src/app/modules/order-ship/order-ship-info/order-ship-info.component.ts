import {Component} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core';
import {OrderShipMeta} from '../order-ship.meta';
import {OrderShipService} from '../order-ship.service';

@Component({
  selector: 'app-order-ship-info',
  templateUrl: './order-ship-info.component.html',
  styleUrls: ['./order-ship-info.component.css'],
  providers: [OrderShipService]
})
export class OrderShipInfoComponent extends AbstractModalComponent<OrderShipMeta> {

  info: any;

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
    return this.formBuilder.group({});
  }

  loaded(): void {
    this.service.loadByID(this.model.id).subscribe(
      value => this.info = JSON.stringify(value, null, 2),
      () => this.info = 'Không tìm thấy'
    );
  }

  remove() {
    this.service.destroy(this.model.id).subscribe(val => {
      this.close({});
    });
  }
}
