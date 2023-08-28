import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PaymentTransactionMeta} from '../payment-transaction.meta';
import {PaymentTransactionService} from '../payment-transaction.service';

@Component({
  selector: 'app-payment-transaction',
  templateUrl: './payment-transaction-list.component.html',
  styleUrls: ['./payment-transaction-list.component.css'],
  providers: [PaymentTransactionService]
})
export class PaymentTransactionListComponent extends AbstractCRUDComponent<PaymentTransactionMeta> {

  constructor(
    service: PaymentTransactionService,
    modal: BsModalService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý giao dịch thanh toán';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      status: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Trạng thái', 'status', 'Chọn một', [
        {
          name: "Tất cả",
          value: "all"
        },
        {
          name: "Thành công",
          value: "COMPLETED"
        },
        {
          name: "Thất bại",
          value: "FAILED"
        }
      ], 'col-md-6', 'name', 'value'),
    ];
  }

  public initNewModel(): PaymentTransactionMeta {
    return new PaymentTransactionMeta();
  }

}
