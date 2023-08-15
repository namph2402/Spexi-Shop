import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BannerGroupMeta} from '../banner-group.meta';
import {BannerGroupService} from '../banner-group.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-banner-edit',
  templateUrl: './banner-group-edit.component.html',
  styleUrls: ['./banner-group-edit.component.css'],
  providers: [BannerGroupService]
})
export class BannerGroupEditComponent extends AbstractModalComponent<BannerGroupMeta> {

  constructor(
    service: BannerGroupService,
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
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên nhóm', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
  }


}
