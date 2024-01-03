import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PostMeta} from '../post.meta';
import {PostService} from '../post.service';
import {PostCreateComponent} from '../post-create/post-create.component';
import {PostEditComponent} from '../post-edit/post-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {PostCategoryService} from '../../post-category/post-category.service';
import {PostTagService} from '../../post-tag/post-tag.service';
import {ObjectUtil} from '../../../core';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {ArticleCommentListComponent} from '../../article-comment/article-comment-list/article-comment-list.component';
import { PostTagAssignListComponent } from '../post-tag-assign-list/post-tag-assign-list.component';
import { PostRelatedListComponent } from '../../post-related/post-related-list/post-related-list.component';
import { PostRelatedMeta } from '../../post-related/post-related.meta';

@Component({
  selector: 'app-Post',
  templateUrl: './Post-list.component.html',
  styleUrls: ['./Post-list.component.css'],
  providers: [PostService, PostCategoryService, PostTagService]
})
export class PostListComponent extends AbstractCRUDComponent<PostMeta> {

  constructor(
    service: PostService,
    modal: BsModalService,
    builder: FormBuilder,
    private categoryService: PostCategoryService,
    private tagService: PostTagService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý bài đăng';
  }

  getCreateModalComponent(): any {
    return PostCreateComponent;
  }

  getEditModalComponent(): any {
    return PostEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  loadAllCategory() {
    return this.categoryService.loadAll();
  }

  loadAllTag() {
    return this.tagService.loadAll();
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      status: new FormControl(null),
      category_id: new FormControl(null),
      tag_id: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', 'loadAllCategory'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          id: 1,
          name: 'Hoạt động',
          value: '1'
        },
        {
          id: 0,
          name: 'Không hoạt động',
          value: '0'
        },
      ]),
      FieldForm.createSelect('Tìm kiếm thẻ', 'tag_id', 'Chọn một', 'loadAllTag'),
    ];
  }

  initNewModel(): PostMeta {
    return new PostMeta();
  }

  onStatusChange(item: PostMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Đã bật';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Đã tắt';
    }
    methodAsync.subscribe((res: PostMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  upOrder(item: PostMeta) {
    (<PostService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: PostMeta) {
    (<PostService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }

  createPost() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PostMeta> = <AbstractModalComponent<PostMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<PostMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editPost(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<PostMeta> = <AbstractModalComponent<PostMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<PostMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  showComment(item: PostMeta) {
    let modalRef = this.modalService.show(ArticleCommentListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    modal.setRelatedModel(item.article);
    let sub = modal.onHidden.subscribe((result: ModalResult<ArticleCommentMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showTags(item: PostMeta) {
    const modalRef = this.modalService.show(PostTagAssignListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-lg'
    });
    let modal: AbstractCRUDModalComponent<PostMeta> = <AbstractCRUDModalComponent<PostMeta>>modalRef.content;
    modal.setRelatedModel(item);
    (item);
    let sub = modal.onHidden.subscribe((result: ModalResult<PostMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showRelated(item: PostMeta) {
    let modalRef = this.modalService.show(PostRelatedListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<PostRelatedMeta> = <AbstractCRUDModalComponent<PostRelatedMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<PostRelatedMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }
}
