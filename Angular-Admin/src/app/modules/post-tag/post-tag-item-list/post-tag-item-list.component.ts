import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {PostTagService} from '../post-tag.service';
import {AppPagination, FieldForm, ModalResult} from '../../../core/common';
import {PostTagItemCreateComponent} from '../post-tag-item-create/post-tag-item-create.component';
import {PostTagMeta} from '../post-tag.meta';
import {PostService} from '../../post/post.service';
import {ObjectUtil} from '../../../core';

@Component({
  selector: 'app-post-tag-item-list',
  templateUrl: './post-tag-item-list.component.html',
  styleUrls: ['./post-tag-item-list.component.css'],
  providers: [PostTagService, PostService]
})
export class PostTagItemListComponent extends AbstractCRUDModalComponent<PostTagMeta> {

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
    return PostTagItemCreateComponent;
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
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
    ];
  }

  initNewModel(): PostTagMeta {
    const model = new PostTagMeta();
    model.id = this.relatedModel.id;
    return model;
  }

  loaded(): void {
  }

  load(): void {
    let param: any = ObjectUtil.combineValue({
      tag_id: this.relatedModel.id,
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.postService.loadTag(param).subscribe((res: any) => {
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
    (<PostTagService>this.service).detachTags(this.relatedModel.id, item).subscribe((res: PostTagMeta) => {
      this.service.toastSuccessfully('Xóa');
      this.load();
    }, () => this.service.toastFailed('Xóa'));
  }

}
