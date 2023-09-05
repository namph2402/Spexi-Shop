import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ShippingFeeMeta} from '../shipping-fee.meta';
import {ShippingFeeService} from '../shipping-fee.service';
import {FieldForm, ModalResult} from '../../../core/common';
import {ProvinceService} from '../../province/province.service';
import {DistrictService} from '../../district/district.service';
import {WardService} from '../../ward/ward.service';
import {ObjectUtil} from '../../../core';

@Component({
  selector: 'app-shipping-fee',
  templateUrl: './shipping-fee-list.component.html',
  styleUrls: ['./shipping-fee-list.component.css'],
  providers: [ShippingFeeService, ProvinceService, DistrictService, WardService]
})
export class ShippingFeeListComponent extends AbstractCRUDComponent<ShippingFeeMeta> {

  constructor(
    service: ShippingFeeService,
    modal: BsModalService,
    builder: FormBuilder,
    private provinceService: ProvinceService,
    private districtService: DistrictService,
    private wardService: WardService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'QUản lý phí ship';
  }

  getCreateModalComponent(): any {
    return null;
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

  loadAllProvinces() {
    return this.provinceService.loadAll();
  }

  loadDistricts(params: any) {
    this.districtService.loadByParams(params).subscribe(value => {
      this.searchControls[1].data = value;
    });
  }

  loadWards(params: any) {
    this.wardService.loadByParams(params).subscribe(value => {
      this.searchControls[2].data = value;
    });
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      province_id: new FormControl(null),
      district_id: new FormControl(null),
      ward_id: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Tìm kiếm theo tỉnh', 'province_id', 'Chọn một', 'loadAllProvinces'),
      FieldForm.createSelect('Tìm kiếm theo huyện', 'district_id', 'Chọn một', []),
      FieldForm.createSelect('Tìm kiếm theo xã', 'ward_id', 'Chọn một', []),
    ];
  }

  initNewModel(): ShippingFeeMeta {
    return new ShippingFeeMeta();
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.searchForm.controls['province_id'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.searchForm.controls['district_id'].setValue(null);
        this.loadDistricts({province_id: value});
      }
    });
    this.searchForm.controls['district_id'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.searchForm.controls['ward_id'].setValue(null);
        this.loadWards({district_id: value});
      }
    });
  }
}
