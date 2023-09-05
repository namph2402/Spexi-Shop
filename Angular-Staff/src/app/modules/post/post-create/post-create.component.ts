import {Component} from '@angular/core';
import {PostMeta} from '../post.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostService} from '../post.service';
import {PostCategoryMeta} from '../../post-category/post-category.meta';
import {PostCategoryService} from '../../post-category/post-category.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-create',
  templateUrl: './post-create.component.html',
  styleUrls: ['./post-create.component.css'],
  providers: [PostService, PostCategoryService]
})
export class PostCreateComponent extends AbstractModalComponent<PostMeta> {

  constructor(
    service: PostService,
    modal: BsModalRef,
    builder: FormBuilder,
    private categoryService: PostCategoryService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllCategories() {
    return this.categoryService.loadAll();
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      category: new FormControl(null, Validators.required),
      category_id: new FormControl(null),
      category_slug: new FormControl(null),
      summary: new FormControl(null),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      image: new FormControl(null, Validators.required),
      content: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Danh mục', 'category', 'Chọn danh mục', 'loadAllCategories'),
      FieldForm.createTextInput('Tiêu đề bài đăng', 'name', 'Nhập tiêu đề'),
      FieldForm.createFileInput('Ảnh đại diện', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
      FieldForm.createHtmlInput('Tóm tắt ', 'summary', {height: '300px'}),
      FieldForm.createHtmlInput('Nội dung ', 'content', {height: '500px'}),
    ];
  }

  loaded(): void {
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['category'].valueChanges.subscribe((value: PostCategoryMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['category_id'].setValue(value[0].id);
        this.formGroup.controls['category_slug'].setValue(value[0].slug);
      }
    });
  }

}
