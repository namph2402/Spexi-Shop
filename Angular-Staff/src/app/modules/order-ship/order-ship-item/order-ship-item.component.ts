import { Component } from '@angular/core';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { OrderMeta } from '../../order/order.meta';
import { OrderShipMeta } from '../order-ship.meta';
import { OrderShipCreateComponent } from '../order-ship-create/order-ship-create.component';
import { OrderShipService } from '../order-ship.service';
import { OrderShipCreateResultComponent } from '../order-ship-create-result/order-ship-create-result.component';
import { AbstractCRUDModalComponent, AbstractModalComponent, AppPagination, FieldForm, ModalResult, ObjectUtil} from '../../../core';

@Component({
  selector: 'app-order-ship-item',
  templateUrl: './order-ship-item.component.html',
  styleUrls: ['./order-ship-item.component.css'],
  providers: [OrderShipService]
})
export class OrderShipItemComponent extends AbstractCRUDModalComponent<OrderShipMeta> {

  statusSelectAll: boolean = false;
  selectors: boolean[];

  constructor(
    service: OrderShipService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  onInit(): void {
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
    return { class: 'modal-lg', ignoreBackdropClick: true, backdrop: 'static', keyboard: false };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { class: 'modal-lg' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      code: new FormControl(null),
      search: new FormControl(null),
      created_date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo mã đơn hàng', 'code', 'Nhập từ khóa'),
      FieldForm.createTextInput('Tìm kiếm theo tên khách hàng', 'search', 'Nhập từ khóa'),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Chọn ngày'),
    ];
  }

  initNewModel(): OrderShipMeta {
    return new OrderShipMeta();
  }

  loaded() {
  }

  load() {
    this.statusSelectAll = false;
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
      order_status: "Chuẩn bị hàng"
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
      this.list = res.data;
      this.pagination.set(res);
      this.selectors = this.list.map(value => false);
    }, () => {
      this.list = [];
      this.pagination = new AppPagination();
      this.nextPage = this.pagination.currentPage;
    }
    );
  }

  ship() {
    let ids: number[] = this.list.filter((val, index) => this.selectors[index]).map(val => val.id);
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(OrderShipCreateComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    modal.setModel({ order_ids: ids.join(',') });
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderShipMeta>) => {
      if (result.success) {
        const modalRef2 = this.modalService.show(OrderShipCreateResultComponent, {
          ignoreBackdropClick: true,
          'class': 'modal-lg'
        });
        let modal2: AbstractCRUDModalComponent<any> = <AbstractCRUDModalComponent<any>>modalRef2.content;
        modal2.setRelatedModel(result.data);
        let sub2 = modal2.onHidden.subscribe((result: ModalResult<OrderShipMeta>) => {
          sub2.unsubscribe();
        });
      }
      this.load();
      sub.unsubscribe();
    });
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

  dismiss() {
    this.modal.hide();
    this.onHidden.emit(new ModalResult<any>('success'));
  }

}
