import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {CustomerMeta} from '../customer.meta';
import {CustomerService} from '../customer.service';
import {FieldForm} from '../../../core';

@Component({
  selector: 'app-customer',
  templateUrl: './customer-list.component.html',
  styleUrls: ['./customer-list.component.css'],
  providers: [CustomerService]
})
export class CustomerListComponent extends AbstractCRUDComponent<CustomerMeta> {

  constructor(
    service: CustomerService,
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
    return 'Quản lý khách hàng';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      status: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          name: 'Hoạt động',
          value: '1'
        },
        {
          name: 'Không hoạt động',
          value: '0'
        },
      ]),
    ];
  }

  initNewModel(): CustomerMeta {
    return new CustomerMeta();
  }

  onStatusChange(item: CustomerMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Mở kích hoạt';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Khóa kích hoạt';
    }
    methodAsync.subscribe((res: CustomerMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }
}
