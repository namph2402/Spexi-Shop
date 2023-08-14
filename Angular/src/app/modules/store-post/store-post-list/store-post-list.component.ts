import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {StorePostMeta} from '../store-post.meta';
import {StorePostService} from '../store-post.service';
import {StorePostCreateComponent} from '../store-post-create/store-post-create.component';
import {StorePostEditComponent} from '../store-post-edit/store-post-edit.component';
import {ModalResult, ObjectUtil} from '../../../core';

@Component({
  selector: 'app-store-post',
  templateUrl: './store-post-list.component.html',
  styleUrls: ['./store-post-list.component.css'],
  providers: [StorePostService]
})
export class StorePostListComponent extends AbstractCRUDComponent<StorePostMeta> {

  constructor(
    service: StorePostService,
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
    return 'Quản lý bài viết';
  }

  getCreateModalComponent(): any {
    return StorePostCreateComponent;
  }

  getEditModalComponent(): any {
    return StorePostEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): StorePostMeta {
    return new StorePostMeta();
  }

  createStorePost() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<StorePostMeta> = <AbstractModalComponent<StorePostMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<StorePostMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editStorePost(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<StorePostMeta> = <AbstractModalComponent<StorePostMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<StorePostMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }
}
