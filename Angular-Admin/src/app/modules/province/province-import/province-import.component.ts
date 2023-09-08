import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ProvinceService} from '../province.service';
import {ProvinceMeta} from '../province.meta';
import {AbstractModalComponent, FieldForm} from '../../../core';

@Component({
  selector: 'app-province-import',
  templateUrl: './province-import.component.html',
  styleUrls: ['./province-import.component.css'],
  providers: [ProvinceService]
})
export class ProvinceImportComponent extends AbstractModalComponent<ProvinceMeta> {

  constructor(
    service: ProvinceService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null),
      file: new FormControl(null, Validators.required),
      note: new FormControl(null)
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
