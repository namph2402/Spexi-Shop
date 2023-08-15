import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PostCategoryMeta} from '../post-category.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostCategoryService} from '../post-category.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-category-create',
  templateUrl: './post-category-create.component.html',
  styleUrls: ['./post-category-create.component.css'],
  providers: [PostCategoryService]
})
export class PostCategoryCreateComponent extends AbstractModalComponent<PostCategoryMeta> {

  constructor(
    service: PostCategoryService,
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
      FieldForm.createTextInput('Tên danh mục', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
  }

}
