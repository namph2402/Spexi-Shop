import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BannerGroupMeta} from '../banner-group.meta';
import {BannerGroupService} from '../banner-group.service';
import {BannerGroupCreateComponent} from '../banner-group-create/banner-group-create.component';
import {BannerGroupEditComponent} from '../banner-group-edit/banner-group-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import {BannerService} from '../../banner/banner.service';
import {BannerMeta} from '../../banner/banner.meta';
import {BannerCreateComponent} from '../../banner/banner-create/banner-create.component';
import {BannerEditComponent} from '../../banner/banner-edit/banner-edit.component';

@Component({
  selector: 'app-banner',
  templateUrl: './banner-group-list.component.html',
  styleUrls: ['./banner-group-list.component.css'],
  providers: [BannerGroupService, BannerService]
})
export class BannerGroupListComponent extends AbstractCRUDComponent<BannerGroupMeta> {

  constructor(
    service: BannerGroupService,
    modal: BsModalService,
    builder: FormBuilder,
    private bannerService: BannerService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý banner';
  }

  getCreateModalComponent(): any {
    return BannerGroupCreateComponent;
  }

  getEditModalComponent(): any {
    return BannerGroupEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
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

  initNewModel(): BannerGroupMeta {
    return new BannerGroupMeta();
  }

  onStatusChange(item: BannerMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Đã bật';
    if (enable) {
      methodAsync = this.bannerService.enable(item.id);
    } else {
      methodAsync = this.bannerService.disable(item.id);
      titleMsg = 'Đã tắt';
    }
    methodAsync.subscribe((res: BannerMeta) => {
      this.bannerService.toastSuccessfully(titleMsg);
    }, () => this.bannerService.toastFailed(titleMsg));
    this.load();
  }


  createGroup() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<BannerGroupMeta> = <AbstractModalComponent<BannerGroupMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<BannerGroupMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editGroup(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<BannerGroupMeta> = <AbstractModalComponent<BannerGroupMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<BannerGroupMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  createBanner(item: BannerGroupMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(BannerCreateComponent, config);
    const modal: AbstractModalComponent<BannerMeta> = <AbstractModalComponent<BannerMeta>>modalRef.content;
    const banner = new BannerMeta();
    banner.group_id = item.id;
    modal.setModel(banner);
    const sub = modal.onHidden.subscribe((result: ModalResult<BannerMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editBanner(item: BannerMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(BannerEditComponent, config);
    const modal: AbstractModalComponent<BannerMeta> = <AbstractModalComponent<BannerMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<BannerMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  removeBanner(item: BannerMeta) {
    this.bannerService.destroy(item['id']).subscribe(() => {
      this.service.toastSuccessfullyDeleted();
      this.load();
    }, () => this.service.toastFailedDeleted());
  }

  upOrder(item: BannerMeta) {
    this.bannerService.up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: BannerMeta) {
    this.bannerService.down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
}
