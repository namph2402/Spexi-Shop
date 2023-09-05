import { Component } from '@angular/core';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { OrderMeta } from '../../order/order.meta';
import { OrderShipMeta } from '../order-ship.meta';
import { OrderShipService } from '../order-ship.service';
import { AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent, AppPagination, FieldForm, ModalResult, ObjectUtil} from '../../../core';
import { OrderShipItemComponent } from '../order-ship-item/order-ship-item.component';
import { OrderShipInfoComponent } from '../order-ship-info/order-ship-info.component';
import { OrderShipNoteComponent } from '../order-ship-note/order-ship-note.component';

@Component({
  selector: 'app-order-ship-list',
  templateUrl: './order-ship-list.component.html',
  styleUrls: ['./order-ship-list.component.css'],
  providers: [OrderShipService]
})
export class OrderShipListComponent extends AbstractCRUDComponent<OrderShipMeta> {

  constructor(
    service: OrderShipService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh sách đơn giao';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { class: 'modal-lg' };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { class: 'modal-lg' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      code: new FormControl(null),
      search: new FormControl(null),
      status: new FormControl(null),
      ship_status: new FormControl(null),
      created_date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo mã đơn hàng', 'code', 'Nhập từ khóa'),
      FieldForm.createTextInput('Tìm kiếm theo khách hàng', 'search', 'Nhập từ khóa'),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Chọn ngày'),
      FieldForm.createSelect('Trạng thái đơn hàng', 'status', 'Chọn một', [
        {
          name: "Lên đơn",
          value: "Lên đơn"
        },
        {
          name: "Xác nhận",
          value: "Xác nhận"
        },
        {
          name: "Chuẩn bị hàng",
          value: "Chuẩn bị hàng"
        },
        {
          name: "Đã chuẩn bị hàng",
          value: "Đã chuẩn bị hàng"
        },
        {
          name: "Đang giao",
          value: "Đang giao"
        },
      ]),
      FieldForm.createSelect('Trạng thái đơn vận', 'ship_status', 'Chọn một', [
        {
          name: "Lên đơn",
          value: "Lên đơn"
        },
        {
          name: "Xác nhận",
          value: "Xác nhận"
        },
        {
          name: "Chuẩn bị hàng",
          value: "Chuẩn bị hàng"
        },
        {
          name: "Đã chuẩn bị hàng",
          value: "Đã chuẩn bị hàng"
        },
        {
          name: "Đang giao",
          value: "Đang giao"
        },
      ]),
    ];
  }

  initNewModel(): OrderShipMeta {
    return new OrderShipMeta();
  }

  showShip() {
    let modalRef = this.modalService.show(OrderShipItemComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<OrderShipMeta> = <AbstractCRUDModalComponent<OrderShipMeta>>modalRef.content;
    modal.setRelatedModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderShipMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  infoShip(ship: OrderShipMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(OrderShipInfoComponent, config);
    let modal: AbstractModalComponent<OrderShipMeta> = <AbstractModalComponent<OrderShipMeta>>modalRef.content;
    modal.setModel(ship);
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderShipMeta>) => {
      if (result.success) {
        this.list[i] = null;
        this.list[i].status = 'Chuẩn bị hàng';
      }
      sub.unsubscribe();
    });
  }

  printShippingBill(item: OrderMeta) {
    (<OrderShipService>this.service).printBill(item.shipping.id).subscribe(res => {
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

  shipping(item: OrderShipMeta) {
    (<OrderShipService>this.service).shipping(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giao hàng');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  complete(item: OrderShipMeta) {
    (<OrderShipService>this.service).complete(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giao hàng');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  note(item: OrderShipMeta, type: number) {
    const modalRef = this.modalService.show(OrderShipNoteComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<OrderShipMeta> = <AbstractModalComponent<OrderShipMeta>>modalRef.content;
    const model = new OrderShipMeta();
    model.type = type;
    model.id = item.id;
    modal.setModel(model);
    const sub = modal.onHidden.subscribe((result: ModalResult<OrderShipMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }


}
