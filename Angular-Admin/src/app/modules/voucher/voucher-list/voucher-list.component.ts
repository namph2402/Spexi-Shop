import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {VoucherMeta} from '../voucher.meta';
import {VoucherService} from '../voucher.service';
import {VoucherCreateComponent} from '../voucher-create/voucher-create.component';
import {VoucherEditComponent} from '../voucher-edit/voucher-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {ObjectUtil} from '../../../core/utils';

@Component({
  selector: 'app-voucher-list',
  templateUrl: './voucher-list.component.html',
  styleUrls: ['./voucher-list.component.css'],
  providers: [VoucherService]
})
export class VoucherListComponent extends AbstractCRUDComponent<VoucherMeta> {

  constructor(
    service: VoucherService,
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
    return 'Quản lý mã giảm giá';
  }

  getCreateModalComponent(): any {
    return VoucherCreateComponent;
  }

  getEditModalComponent(): any {
    return VoucherEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      status: new FormControl(null),
      type: new FormControl(null)
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          name: 'Hoạt động',
          value: 1
        },
        {
          name: 'Không hoạt động',
          value: 0
        },
      ]),
      FieldForm.createSelect('Loại voucher', 'type', 'Chọn một', [
        {
          name: 'Giảm giá đơn hàng',
          value: 1
        }, {
          name: 'Free ship',
          value: 2
        }
      ]),
    ];
  }

  initNewModel(): VoucherMeta {
    return new VoucherMeta();
  }

  onStatusChange(item: VoucherMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Đã bật';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Đã tắt';
    }
    methodAsync.subscribe((res: VoucherMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createVoucher() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<VoucherMeta> = <AbstractModalComponent<VoucherMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<VoucherMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editVoucher(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<VoucherMeta> = <AbstractModalComponent<VoucherMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<VoucherMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }
}
