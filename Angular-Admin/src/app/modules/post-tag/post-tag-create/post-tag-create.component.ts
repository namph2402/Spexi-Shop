import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PostTagMeta} from '../post-tag.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostTagService} from '../post-tag.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-tag-create',
  templateUrl: './post-tag-create.component.html',
  styleUrls: ['./post-tag-create.component.css'],
  providers: [PostTagService]
})
export class PostTagCreateComponent extends AbstractModalComponent<PostTagMeta> {

  constructor(
    service: PostTagService,
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
      FieldForm.createTextInput('Tên tag', 'name', 'Nhập kí tự'),
    ];
  }

  loaded(): void {
  }

}
