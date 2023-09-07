import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {PaymentMethodMeta} from '../payment-method.meta';
import {PaymentMethodService} from '../payment-method.service';
import {PaymentMethodCreateComponent} from '../payment-method-create/payment-method-create.component';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {PaymentMethodEditVnpayComponent} from '../payment-method-vnpay-edit/payment-method-vnpay-edit.component';

@Component({
  selector: 'app-payment-method',
  templateUrl: './payment-method-list.component.html',
  styleUrls: ['./payment-method-list.component.css'],
  providers: [PaymentMethodService]
})
export class PaymentMethodListComponent extends AbstractCRUDComponent<PaymentMethodMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Cấu hình thanh toán';
  }

  getCreateModalComponent(): any {
    return PaymentMethodCreateComponent;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initNewModel(): PaymentMethodMeta {
    return new PaymentMethodMeta();
  }

  constructor(
    service: PaymentMethodService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onStatusChange(item: PaymentMethodMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: PaymentMethodMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createPayment() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PaymentMethodMeta> = <AbstractModalComponent<PaymentMethodMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<PaymentMethodMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  addVnpay(item: PaymentMethodMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(PaymentMethodEditVnpayComponent, config);
    const modal: AbstractModalComponent<PaymentMethodMeta> = <AbstractModalComponent<PaymentMethodMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<PaymentMethodMeta>) => {
      if (result.success) {
            this.load();
          }
      sub.unsubscribe();
    });
  }

  destroyConfig(item: PaymentMethodMeta, i: number) {
    (<PaymentMethodService>this.service).destroyConfig(item.id).subscribe(res => {
      this.service.toastSuccessfully('Xóa');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

}
