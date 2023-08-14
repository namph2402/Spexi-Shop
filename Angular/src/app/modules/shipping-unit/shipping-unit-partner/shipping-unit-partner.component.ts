import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { AbstractModalComponent } from '../../../core/crud';
import { ShippingUnitMeta } from '../shipping-unit.meta';
import { ShippingUnitService } from '../shipping-unit.service';
import { FieldForm } from '../../../core/common';

@Component({
  selector: 'app-shipping-unit-partner',
  templateUrl: './shipping-unit-partner.component.html',
  styleUrls: ['./shipping-unit-partner.component.css'],
  providers: [ShippingUnitService]
})


export class ShippingUnitPartnerComponent extends AbstractModalComponent<ShippingUnitMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      store: new FormControl(null, [Validators.pattern('[^ ].*$')]),
      service: new FormControl(null, [Validators.pattern('[^ ].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Kho chứa', 'store', 'Nhập tên kho'),
      FieldForm.createTextInput('Dịch vụ vận chuyển', 'service', 'Nhập tên dịch vụ vận chuyển'),
    ];
  }

  constructor(
    service: ShippingUnitService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  loaded(): void {
  }

  createUnitPartner() {
    let item = this.prepareParams();
    (<ShippingUnitService>this.service).createUnitPartner(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedEdited());
  }
}
