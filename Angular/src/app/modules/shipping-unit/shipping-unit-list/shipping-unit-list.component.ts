import { Component } from '@angular/core';
import { BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ShippingUnitMeta } from '../shipping-unit.meta';
import { ShippingUnitService } from '../shipping-unit.service';
import { ShippingUnitCreateComponent } from '../shipping-unit-create/shipping-unit-create.component';
import { ShippingUnitEditComponent } from '../shipping-unit-edit/shipping-unit-edit.component';
import { AbstractCRUDComponent, AbstractModalComponent } from '../../../core/crud';
import { FieldForm, ModalResult } from '../../../core/common';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';
import { ShippingStoreMeta } from '../../shipping-store/shipping-store.meta';
import { ShippingServiceMeta } from '../../shipping-service/shipping-service.meta';
import { ShippingUnitPartnerComponent } from '../shipping-unit-partner/shipping-unit-partner.component';
import { ShippingStoreService } from '../../shipping-store/shipping-store.service';
import { ShippingServiceService } from '../../shipping-service/shipping-service.service';

@Component({
  selector: 'app-shipping-unit-list',
  templateUrl: './shipping-unit-list.component.html',
  styleUrls: ['./shipping-unit-list.component.css'],
  providers: [ShippingUnitService, ShippingServiceService, ShippingStoreService]
})

export class ShippingUnitListComponent extends AbstractCRUDComponent<ShippingUnitMeta> {

  stores = new ShippingStoreMeta();
  services = new ShippingServiceMeta();

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Đối tác vận chuyển';
  }

  getCreateModalComponent(): any {
    return ShippingUnitCreateComponent;
  }

  getEditModalComponent(): any {
    return ShippingUnitEditComponent;
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
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): ShippingUnitMeta {
    return new ShippingUnitMeta();
  }

  constructor(
    service: ShippingUnitService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    protected shippingServiceService: ShippingServiceService,
    protected shippingStoreService: ShippingStoreService,
  ) {
    super(service, modal, builder,);
  }

  createShippingUnit() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ShippingUnitMeta> = <AbstractModalComponent<ShippingUnitMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ShippingUnitMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editShippingUnit(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ShippingUnitMeta> = <AbstractModalComponent<ShippingUnitMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ShippingUnitMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  sync(item: ShippingUnitMeta) {
    (<ShippingUnitService>this.service).sync(item.id).subscribe(res => {
      this.service.toastSuccessfully('Đồng bộ');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  removeStore(item: ShippingStoreMeta) {
    (<ShippingStoreService>this.shippingStoreService).destroy(item.id).subscribe(res => {
      this.shippingStoreService.toastSuccessfully('Xóa cửa hàng');
      this.load();
    }, () => this.shippingStoreService.toastFailedEdited());
  }

  removeService(item: ShippingServiceMeta) {
    (<ShippingServiceService>this.shippingServiceService).destroy(item.id).subscribe(res => {
      this.service.toastSuccessfully('Xóa dịch vụ');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  addUnitPartner() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(ShippingUnitPartnerComponent, config);
    let modal: AbstractModalComponent<ShippingUnitMeta> = <AbstractModalComponent<ShippingUnitMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ShippingUnitMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }
}
