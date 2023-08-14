import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BannerMeta} from '../banner.meta';
import {BannerService} from '../banner.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-banner-edit',
  templateUrl: './banner-edit.component.html',
  styleUrls: ['./banner-edit.component.css'],
  providers: [BannerService]
})
export class BannerEditComponent extends AbstractModalComponent<BannerMeta> {
  image: any;

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
      FieldForm.createTextInput('Tên', 'name', 'Nhập  giá trị'),
      FieldForm.createTextArea('Mô tả', 'summary', 'Nhập  giá trị'),
      FieldForm.createTextInput('Đường dẫn', 'href', 'Nhập  giá trị'),
      FieldForm.createFileInput('Chọn ảnh đại diện', 'image', 'Chọn ảnh', this.onFileUploadChange),
    ];
  }

  loaded(): void {
  }

}
