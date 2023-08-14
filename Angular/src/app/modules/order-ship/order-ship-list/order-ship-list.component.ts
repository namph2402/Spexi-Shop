import { Component } from '@angular/core';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { OrderMeta } from '../../order/order.meta';
import { OrderShipMeta } from '../order-ship.meta';
import { OrderShipService } from '../order-ship.service';
import { AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent, AppPagination, FieldForm, ModalResult, ObjectUtil} from '../../../core';
import { OrderShipItemComponent } from '../order-ship-item/order-ship-item.component';
import { OrderShipInfoComponent } from '../order-ship-info/order-ship-info.component';

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
      customer_phone: new FormControl(null),
      created_date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo mã đơn hàng', 'code', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createTextInput('Tìm kiếm theo tên khách hàng', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createNumberInput('Tìm kiếm theo số điện thoại', 'customer_phone', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Chọn ngày', 'col-md-6'),
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
        // this.list[i] = null;
        this.list[i].status = 'Chuẩn bị hàng';
      }
      sub.unsubscribe();
    });
  }

}
