import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostTagMeta} from '../post-tag.meta';
import {PostTagService} from '../post-tag.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-category-edit',
  templateUrl: './post-tag-edit.component.html',
  styleUrls: ['./post-tag-edit.component.css'],
  providers: [PostTagService]
})
export class PostTagEditComponent extends AbstractModalComponent<PostTagMeta> {

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
      FieldForm.createTextInput('Tên tag', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
  }

}
