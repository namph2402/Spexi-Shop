import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {OrderMeta} from './../order.meta';
import {OrderService} from './../order.service';
import {OrderCreateComponent} from './../order-create/order-create.component';
import {OrderEditComponent} from './../order-edit/order-edit.component';
import {AbstractCRUDComponent, AbstractModalComponent, FieldForm, ModalResult, ObjectUtil} from '../../../core';
import { OrderNoteComponent } from '../order-note/order-note.component';
import { OrderConfirmComponent } from '../order-confirm/order-confirm.component';

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
    return 'Đơn hàng của tôi';
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
      status: new FormControl(null),
      customer_phone: new FormControl(null),
      created_date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên khách hàng', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createNumberInput('Tìm kiếm theo số điện thoại', 'customer_phone', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Trạng thái', 'status', 'Chọn một', [], 'col-md-6', 'name', 'value'),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Chọn ngày', 'col-md-6'),
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
    const modalRef = this.modalService.show(OrderNoteComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }
}
