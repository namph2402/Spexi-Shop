import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {OrderMeta} from './../order.meta';
import {OrderService} from './../order.service';
import {OrderCreateComponent} from './../order-create/order-create.component';
import {OrderEditComponent} from './../order-edit/order-edit.component';
import {AbstractCRUDComponent, AbstractModalComponent, FieldForm, ModalResult, ObjectUtil} from '../../../core';
import {OrderCancelComponent} from '../order-cancel/order-cancel.component';
import {OrderConfirmComponent} from '../order-confirm/order-confirm.component';
import {OrderRefundComponent} from '../order-refund/order-refund.component';

@Component({
  selector: 'app-order-list',
  templateUrl: './order-list.component.html',
  styleUrls: ['./order-list.component.css'],
  providers: [OrderService],
})
export class OrderListComponent extends AbstractCRUDComponent<OrderMeta> {

  constructor(
    service: OrderService,
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
    return 'Đơn hàng';
  }

  getCreateModalComponent(): any {
    return OrderCreateComponent;
  }

  getEditModalComponent(): any {
    return OrderEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      order_status: new FormControl(null),
      code: new FormControl(null),
      created_date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo mã đơn', 'code', 'Nhập từ khóa'),
      FieldForm.createTextInput('Tìm kiếm theo khách hàng', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Trạng thái đơn hàng', 'order_status', 'Chọn một', [
        {
          name: 'Lên đơn',
          value: 'Lên đơn'
        },
        {
          name: 'Xác nhận',
          value: 'Xác nhận'
        },
        {
          name: 'Chuẩn bị hàng',
          value: 'Chuẩn bị hàng'
        },
        {
          name: 'Đã chuẩn bị hàng',
          value: 'Đã chuẩn bị hàng'
        },
        {
          name: 'Đang giao',
          value: 'Đang giao'
        },
        {
          name: 'Hoàn thành',
          value: 'Hoàn thành'
        },
        {
          name: 'Hủy đơn',
          value: 'Hủy đơn'
        },
      ]),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Chọn ngày'),
    ];
  }

  initNewModel(): OrderMeta {
    return new OrderMeta();
  }

  createOrder() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editOrder(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  confirm(item: OrderMeta) {
    const modalRef = this.modalService.show(OrderConfirmComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  cancel(item: OrderMeta) {
    const modalRef = this.modalService.show(OrderCancelComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  refund(item: OrderMeta) {
    const modalRef = this.modalService.show(OrderRefundComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  prepare(item: OrderMeta) {
    (<OrderService>this.service).prepare(item.id).subscribe(res => {
      this.service.toastSuccessfully('Chuẩn bị hàng');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  return(item: OrderMeta) {
    (<OrderService>this.service).return(item.id).subscribe(res => {
      this.service.toastSuccessfully('Hoàn hàng');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  returned(item: OrderMeta) {
    (<OrderService>this.service).returned(item.id).subscribe(res => {
      this.service.toastSuccessfully('Hoàn hàng');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
}
