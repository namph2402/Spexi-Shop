import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormGroup} from '@angular/forms';
import {ArticleCommentService} from '../article-comment.service';
import {ArticleCommentMeta} from '../article-comment.meta';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, ModalResult} from '../../../core/common';

@Component({
  selector: 'app-article-comment',
  templateUrl: './article-comment-list.component.html',
  styleUrls: ['./article-comment-list.component.css'],
  providers: [ArticleCommentService]
})
export class ArticleCommentListComponent extends AbstractCRUDModalComponent<ArticleCommentMeta> {

  article: boolean = false;

  constructor(
    service: ArticleCommentService,
    modal: BsModalRef,
    modalService: BsModalService,
    builder: FormBuilder
  ) {

    super(service, modal, modalService, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Bình luận bài viết';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-small'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-small'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  initNewModel(): ArticleCommentMeta {
    const model = new ArticleCommentMeta();
    model.article_id = this.relatedModel.id;
    return model;
  }

  loaded(): void {
  }

  load(): void {
    if (this.relatedModel != null) {
      this.article = true;
      let param = {
        article_id: this.relatedModel.id,
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
    } else {
      this.service.toastFailed('Chưa có bài viết');
    }
  }

  onStatusChange(item: ArticleCommentMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Đã duyệt';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Chờ duyệt';
    }
    methodAsync.subscribe((res: ArticleCommentMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }
}
