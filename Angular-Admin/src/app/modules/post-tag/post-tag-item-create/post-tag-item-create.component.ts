import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm, ModalResult} from '../../../core/common';
import {PostService} from '../../post/post.service';
import {PostMeta} from '../../post/post.meta';
import {ObjectUtil} from '../../../core';
import {PostTagService} from '../../post-tag/post-tag.service';
import {PostTagMeta} from '../post-tag.meta';
import {PostCategoryService} from '../../post-category/post-category.service';

@Component({
  selector: 'app-post-tag-item-create',
  templateUrl: './post-tag-item-create.component.html',
  styleUrls: ['./post-tag-item-create.component.css'],
  providers: [PostTagService, PostService, PostCategoryService]
})
export class PostTagItemCreateComponent extends AbstractModalComponent<PostTagMeta> {

  list = [];

  constructor(
    service: PostTagService,
    modal: BsModalRef,
    builder: FormBuilder,
    private postService: PostService,
    private categoryService: PostCategoryService,
  ) {
    super(service, modal, builder);
    this.loadAllCategory();
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      tag_id_add: new FormControl(null, Validators.required),
      category_id: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', [], 'col-md-6'),
    ];
  }

  loaded(): void {
    this.formGroup.controls['tag_id_add'].setValue(this.model.id);
    let param: any = ObjectUtil.combineValue({}, this.formGroup.value, true);
    (<PostService>this.postService).loadTag(param).subscribe((res: any) => {
      this.list = res;
    }, () => {
      this.list = [];
    });
  }

  loadAllCategory() {
    return this.categoryService.loadAll().subscribe(value => {
      this.fields[1].data = value;
    });
  }

  assignAll() {
    let ids: number[] = this.list.map(v => v.id);
    (<PostTagService>this.service).attachTags(this.model.id, ids).subscribe((res: PostMeta) => {
      this.service.toastSuccessfully('Thêm tag');
      this.close(ObjectUtil.mergeValue(this.model, res));
    }, () => this.service.toastFailed('Thêm tag'));
  }

  assign(item: PostMeta) {
    (<PostTagService>this.service).attachTags(this.model.id, [item.id]).subscribe((res: PostMeta) => {
      this.service.toastSuccessfully('Thêm tag');
      this.loaded();
    }, () => this.service.toastFailed('Thêm tag'));
  }

  dismiss() {
    this.modal.hide();
    this.onHidden.emit(new ModalResult<any>('success'));
  }

  removeFilter() {
    this.formGroup.reset();
    this.loaded();
  }
}
