import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PostCategoryMeta} from '../post-category.meta';
import {PostCategoryService} from '../post-category.service';
import {PostCategoryCreateComponent} from '../post-category-create/post-category-create.component';
import {PostCategoryEditComponent} from '../post-category-edit/post-category-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';

@Component({
  selector: 'app-post-category',
  templateUrl: './post-category-list.component.html',
  styleUrls: ['./post-category-list.component.css'],
  providers: [PostCategoryService]
})
export class PostCategoryListComponent extends AbstractCRUDComponent<PostCategoryMeta> {

  constructor(
    service: PostCategoryService,
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
    return 'Quản lý danh mục bài viết';
  }

  getCreateModalComponent(): any {
    return PostCategoryCreateComponent;
  }

  getEditModalComponent(): any {
    return PostCategoryEditComponent;
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
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
    ];
  }

  initNewModel(): PostCategoryMeta {
    return new PostCategoryMeta();
  }

  createPostCategory() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PostCategoryMeta> = <AbstractModalComponent<PostCategoryMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<PostCategoryMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editPostCategory(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<PostCategoryMeta> = <AbstractModalComponent<PostCategoryMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<PostCategoryMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  upOrder(item: PostCategoryMeta) {
    (<PostCategoryService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: PostCategoryMeta) {
    (<PostCategoryService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }

}
