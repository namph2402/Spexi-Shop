import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StorePostService} from '../store-post.service';
import {StorePostMeta} from '../store-post.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-store-post-create',
  templateUrl: './store-post-create.component.html',
  styleUrls: ['./store-post-create.component.css'],
  providers: [StorePostService]
})
export class StorePostCreateComponent extends AbstractModalComponent<StorePostMeta> {

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
