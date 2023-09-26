import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PostTagMeta} from '../post-tag.meta';
import {PostTagService} from '../post-tag.service';
import {PostTagCreateComponent} from '../post-tag-create/post-tag-create.component';
import {PostTagEditComponent} from '../post-tag-edit/post-tag-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {ObjectUtil} from '../../../core';
import {PostTagItemListComponent} from '../post-tag-item-list/post-tag-item-list.component';

@Component({
  selector: 'app-post-tag',
  templateUrl: './post-tag-list.component.html',
  styleUrls: ['./post-tag-list.component.css'],
  providers: [PostTagService]
})
export class PostTagListComponent extends AbstractCRUDComponent<PostTagMeta> {

  constructor(
    service: PostTagService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tag bài đăng';
  }

  getCreateModalComponent(): any {
    return PostTagCreateComponent;
  }

  getEditModalComponent(): any {
    return PostTagEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
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
    return new PostTagMeta();
  }

  onStatusChange(item: PostTagMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: PostTagMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createPostTag() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PostTagMeta> = <AbstractModalComponent<PostTagMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<PostTagMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editPostTag(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<PostTagMeta> = <AbstractModalComponent<PostTagMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<PostTagMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  showPost(item: PostTagMeta) {
    let modalRef = this.modalService.show(PostTagItemListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<PostTagMeta> = <AbstractCRUDModalComponent<PostTagMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<PostTagMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

}
