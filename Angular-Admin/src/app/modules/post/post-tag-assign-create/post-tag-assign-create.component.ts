import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {PostService} from '../../post/post.service';
import {PostMeta} from '../../post/post.meta';
import {ObjectUtil} from '../../../core';
import { PostTagService } from '../../post-tag/post-tag.service';

@Component({
  selector: 'app-post-tag-assign-create',
  templateUrl: './post-tag-assign-create.component.html',
  styleUrls: ['./post-tag-assign-create.component.css'],
  providers: [PostService, PostTagService]
})
export class PostTagAssignCreateComponent extends AbstractModalComponent<PostMeta> {

  constructor(
    service: PostService,
    modal: BsModalRef,
    builder: FormBuilder,
    private tagService: PostTagService
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllTags() {
    return this.tagService.loadByParams({post_id_add: this.model.id});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      post_id: new FormControl(null),
      ids: new FormControl(null, Validators.required),
      tags: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createMultiSelect2('Thẻ sản phẩm', 'tags', 'Chọn nhiều', 'loadAllTags')
    ];
  }

  loaded(): void {
    this.formGroup.controls['post_id'].setValue(this.model.id);
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['tags'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['ids'].setValue(value.map(v => v.id));
      } else {
        this.formGroup.controls['ids'].setValue(null);
      }
    });
  }

  assign() {
    let ids: number[] = this.formGroup.controls['ids'].value;
    (<PostService>this.service).attachTags(this.model.id, ids).subscribe((res: PostMeta) => {
      this.service.toastSuccessfully('Thêm tag');
      this.close(ObjectUtil.mergeValue(this.model, res));
    }, () => this.service.toastFailed('Thêm tag'));
  }
}
