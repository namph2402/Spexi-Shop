import {Component} from '@angular/core';
import {ShippingFeeMeta} from '../shipping-fee.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingFeeService} from '../shipping-fee.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {ProvinceService} from '../../province/province.service';
import {DistrictService} from '../../district/district.service';
import {WardService} from '../../ward/ward.service';

@Component({
  selector: 'app-shipping-fee-create',
  templateUrl: './shipping-fee-create.component.html',
  styleUrls: ['./shipping-fee-create.component.css'],
  providers: [ShippingFeeService, ProvinceService, DistrictService, WardService]
})
export class ShippingFeeCreateComponent extends AbstractModalComponent<ShippingFeeMeta> {

  constructor(
    service: ShippingFeeService,
    modal: BsModalRef,
    builder: FormBuilder,
    private provinceService: ProvinceService,
    private districtService: DistrictService,
    private wardService: WardService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllProvinces() {
    return this.provinceService.loadAll();
  }

  loadDistricts(params: any) {
    this.districtService.loadByParams(params).subscribe(value => {
      this.fields[1].data = value;
    });
  }

  loadWards(params: any) {
    this.wardService.loadByParams(params).subscribe(value => {
      this.fields[2].data = value;
    });
  }

  loaded(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      province_id: new FormControl(null, Validators.required),
      district_id: new FormControl(null, Validators.required),
      ward_id: new FormControl(null, Validators.required),
      fee: new FormControl(null, [Validators.required, Validators.min(0)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Tìm kiếm theo tỉnh', 'province_id', 'Chọn một', 'loadAllProvinces'),
      FieldForm.createSelect('Tìm kiếm theo huyện', 'district_id', 'Chọn một', []),
      FieldForm.createSelect('Tìm kiếm theo xã', 'ward_id', 'Chọn một', []),
      FieldForm.createNumberInput('Tiền ship', 'fee', 'Nhập tiền ship'),
    ];
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['province_id'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['district_id'].setValue(null);
        this.loadDistricts({province_id: value});
      }
    });
    this.formGroup.controls['district_id'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['ward_id'].setValue(null);
        this.loadWards({district_id: value});
      }
    });
  }
}
