import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StorePostMeta} from '../store-post.meta';
import {StorePostService} from '../store-post.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-store-post-edit',
  templateUrl: './store-post-edit.component.html',
  styleUrls: ['./store-post-edit.component.css'],
  providers: [StorePostService]
})
export class StorePostEditComponent extends AbstractModalComponent<StorePostMeta> {

  constructor(
    service: StorePostService,
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
      content: new FormControl(null, [Validators.required]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createHtmlInput('Nội dung', 'content', {height: '300px'}),
    ];
  }

  loaded(): void {
  }


}
