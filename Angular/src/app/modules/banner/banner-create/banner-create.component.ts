import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BannerMeta} from '../banner.meta';
import {BannerService} from '../banner.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-banner-create',
  templateUrl: './banner-create.component.html',
  styleUrls: ['./banner-create.component.css'],
  providers: [BannerService]
})
export class BannerCreateComponent extends AbstractModalComponent<BannerMeta> {

  constructor(
    service: BannerService,
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
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      summary: new FormControl(null, [Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      image: new FormControl(null, Validators.required),
      href: new FormControl(null, [Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      group_id: new FormControl()
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Mô tả', 'summary', 'Nhập mô tả'),
      FieldForm.createTextInput('Đường dẫn', 'href', 'Nhập đường dẫn'),
      FieldForm.createFileInput('Chọn ảnh banner', 'image', 'Chọn ảnh', this.onFileUploadChange),
    ];
  }

  loaded(): void {
    this.formGroup.controls['group_id'].setValue(this.model.group_id);
  }

}
