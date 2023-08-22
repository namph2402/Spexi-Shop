import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderShipService} from '../order-ship.service';
import {AbstractModalComponent, FieldForm} from '../../../core';
import {ShippingUnitService} from '../../shipping-unit/shipping-unit.service';
import {ShippingServiceService} from '../../shipping-service/shipping-service.service';
import {ShippingStoreService} from '../../shipping-store/shipping-store.service';

@Component({
  selector: 'app-order-ship-create',
  templateUrl: './order-ship-create.component.html',
  styleUrls: ['./order-ship-create.component.css'],
  providers: [OrderShipService, ShippingUnitService, ShippingServiceService, ShippingStoreService]
})
export class OrderShipCreateComponent extends AbstractModalComponent<any> {

  constructor(
    service: OrderShipService,
    modal: BsModalRef,
    builder: FormBuilder,
    private unitService: ShippingUnitService,
    private serviceService: ShippingServiceService,
    private storeService: ShippingStoreService,
  ) {
    super(service, modal, builder);
    this.formGroup.get('unit_id').valueChanges.subscribe(value => {
      if (value) {
        this.loadShippingStoreByUnit(value);
        this.loadShippingServiceByUnit(value);
      }
    });
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllShippingUnit() {
    return this.unitService.loadAll();
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      order_ids: new FormControl(null, Validators.required),
      unit_id: new FormControl(null, [Validators.required]),
      store_id: new FormControl(null, [Validators.required]),
      service_id: new FormControl(null, [Validators.required])
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Chọn bên giao hàng', 'unit_id', 'Chọn một', 'loadAllShippingUnit', ['id', 'name']),
      FieldForm.createSelect('Chọn kho giao', 'store_id', 'Chọn một', [], ['id', 'name']),
      FieldForm.createSelect('Chọn dịch vụ giao', 'service_id', 'Chọn một', [], ['id', 'name']),
    ];
  }

  loaded(): void {
  }

  loadShippingStoreByUnit(value: number) {
    this.storeService.loadByPage({unit_id: value}).subscribe(val => {
        this.fields[1].data = val;
        this.formGroup.get('store_id').setValue(val[0].id);
      }
    );
  }

  loadShippingServiceByUnit(value: number) {
    this.serviceService.loadByPage({unit_id: value}).subscribe(val => {
        this.fields[2].data = val;
        this.formGroup.get('service_id').setValue(val[0].id);
      }
    );
  }
}
