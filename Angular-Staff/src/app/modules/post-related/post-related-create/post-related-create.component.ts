import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {PostRelatedMeta} from '../post-related.meta';
import {PostRelatedService} from '../post-related.service';
import {PostMeta} from '../../post/post.meta';
import { ObjectUtil } from '../../../core';
import { PostCategoryService } from '../../post-category/post-category.service';

@Component({
  selector: 'app-post-related-create',
  templateUrl: './post-related-create.component.html',
  styleUrls: ['./post-related-create.component.css'],
  providers: [PostRelatedService, PostCategoryService]
})
export class PostRelatedCreateComponent extends AbstractModalComponent<PostRelatedMeta> {

  list = [];

  formSearchPost: FormGroup = new FormGroup({
    search: new FormControl(null, Validators.maxLength(255)),
    category_id: new FormControl(null),
  });

  constructor(
    service: PostRelatedService,
    modal: BsModalRef,
    builder: FormBuilder,
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
      category_id: new FormControl(null),
      post_id: new FormControl(null),
      related_id: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', ''),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', []),
    ];
  }

  loadAllCategory() {
    return this.categoryService.loadAll().subscribe(value => {
      this.fields[1].data = value;
    });
  }


  loaded(): void {
    this.load();
  }

  load(): void {
    this.formGroup.controls['post_id'].setValue(this.model.post_id);
    let param: any = ObjectUtil.combineValue({}, this.formGroup.value, true);
    (<PostRelatedService>this.service).loadPosts(param).subscribe((res: any) => {
      this.list = res;
    }, () => {
      this.list = [];
    });
  }

  addRelated(item: PostMeta) {
    let data: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    data['post_id'] = this.model.post_id;
    data['related_id'] = item.id;
    (<PostRelatedService>this.service).addRelated(data).subscribe((res: any) => {
      this.service.toastSuccessfully('Thêm sản phẩm');
      this.load();
    }, () => this.service.toastFailed('Thêm sản phẩm'));
  }

  removeFilter() {
    this.formGroup.reset();
    this.load();
  }
}
