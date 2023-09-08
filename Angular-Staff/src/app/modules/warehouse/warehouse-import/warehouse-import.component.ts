import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {WarehouseService} from '../warehouse.service';
import {WarehouseMeta} from '../warehouse.meta';
import {AbstractModalComponent, FieldForm} from '../../../core';

@Component({
  selector: 'app-warehouse-import',
  templateUrl: './warehouse-import.component.html',
  styleUrls: ['./warehouse-import.component.css'],
  providers: [WarehouseService]
})
export class WarehouseImportComponent extends AbstractModalComponent<WarehouseMeta> {

  constructor(
    service: WarehouseService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
      file: new FormControl(null, Validators.required),
      note: new FormControl(null, Validators.required)
    });
  }

  onFileUploadChange(event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      this.formGroup.controls['file'].setValue(input.files[0]);
    }
  }

  initFieldForm(): FieldForm[] {
    return [];
  }

  loaded(): void {
  }

  import() {
    this.service.import(this.formGroup.get('file').value, this.formGroup.value).subscribe(res => {
      this.service.toastSuccessfully('Nhập file', 'Thành công');
      this.close({});
    }, () => this.service.toastFailedCreated());
    ;
  }
}
