import {Component} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {OrderMeta} from '../../order/order.meta';
import {OrderService} from '../../order/order.service';
import {AbstractCRUDModalComponent} from '../../../core';
import {OrderShipService} from '../order-ship.service';

@Component({
  selector: 'app-order-ship-create-result',
  templateUrl: './order-ship-create-result.component.html',
  styleUrls: ['./order-ship-create-result.component.css'],
  providers: [OrderService, OrderShipService]
})

export class OrderShipCreateResultComponent extends AbstractCRUDModalComponent<any> {

  statusSelectAll: boolean = false;
  selectors: boolean[];

  constructor(
    service: OrderService,
    modal: BsModalRef,
    modalService: BsModalService,
    builder: FormBuilder,
    private shipService: OrderShipService
  ) {
    super(service, modal, modalService, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): any {
  }

  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return undefined;
  }

  buildSearchForm(): FormGroup {
    return undefined;
  }

  initNewModel(): any {
    return undefined;
  }

  loaded(): void {
  }

  load(): void {
    this.list = this.relatedModel;
    this.selectors = this.list.map(val => false);
  }

  selectAll() {
    this.statusSelectAll = !this.statusSelectAll;
    this.selectors = this.selectors.map(val => this.statusSelectAll);
  }

  selectItem(event: OrderMeta, index: number) {
    this.selectors[index] = !this.selectors[index];
    let status = true;
    this.selectors.forEach(val => {
      if (!val) {
        status = false;
      }
    });
    this.statusSelectAll = status;
  }

  printShipBill() {
    let ids: number[] = this.list.filter((val, index) => this.selectors[index]).map(val => val.id);
    (<OrderShipService>this.shipService).printBills(ids).subscribe(res => {
      this.service.toastSuccessfully('In vận đơn');
      if (res['link']) {
        window.open(res['link']);
      }
      if (res['src']) {
        var win = window.open('', 'In đơn hàng', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=200,top=' + (screen.height - 400) + ',left=' + (screen.width - 840));
        win.document.body.innerHTML = res['src'];
      }
      this.load();
    }, () => this.service.toastFailed('In vận đơn'));
  }
}
