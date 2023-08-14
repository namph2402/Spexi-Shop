import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {PostTagService} from '../post-tag.service';
import {PostService} from '../../post/post.service';
import {AppPagination, FieldForm, ModalResult} from '../../../core/common';
import {PostTagAssignCreateComponent} from '../post-tag-assign-create/post-tag-assign-create.component';
import {PostTagMeta} from '../post-tag.meta';
import {PostMeta} from '../../post/post.meta';

@Component({
  selector: 'app-post-tag-assign-list',
  templateUrl: './post-tag-assign-list.component.html',
  styleUrls: ['./post-tag-assign-list.component.css'],
  providers: [PostTagService, PostService]
})
export class PostTagAssignListComponent extends AbstractCRUDModalComponent<PostTagMeta> {

  constructor(
    service: PostTagService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
    private postService: PostService
  ) {
    super(service, modalRef, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý gán tag';
  }

  getCreateModalComponent() {
    return PostTagAssignCreateComponent;
  }

  getEditModalComponent() {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  initSearchForm(): FieldForm[] {
    return [];
  }

  initNewModel(): PostTagMeta {
    const model = new PostTagMeta();
    model.id = this.relatedModel.id;
    model.existsTags = this.list;
    return model;
  }

  loaded(): void {
  }

  load(): void {
    let param = {
      post_id: this.relatedModel.id,
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    };
    this.service.loadByPage(param).subscribe((res: any) => {
      this.nextPage = this.pagination.currentPage;
      this.list = res.data;
      this.pagination.set(res);
    }, () => {
      this.list = [];
      this.pagination = new AppPagination();
      this.nextPage = this.pagination.currentPage;
    });
  }

  createTag() {
    let modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<PostTagMeta> = <AbstractModalComponent<PostTagMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<PostTagMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  detach(item) {
    (<PostService>this.postService).detachTags(this.relatedModel.id, item).subscribe((res: PostMeta) => {
      this.service.toastSuccessfully('Xóa tag');
      this.load();
    }, () => this.service.toastFailed('Xóa tag'));
  }

}
