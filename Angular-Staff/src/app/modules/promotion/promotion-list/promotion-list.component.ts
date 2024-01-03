import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PromotionMeta} from '../promotion.meta';
import {PromotionService} from '../promotion.service';
import {PromotionCreateComponent} from '../promotion-create/promotion-create.component';
import {PromotionEditComponent} from '../promotion-edit/promotion-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {ObjectUtil} from '../../../core/utils';
import {PromotionProductMeta} from '../../promotion-product/promotion-product.meta';
import {PromotionProductListComponent} from '../../promotion-product/promotion-product-list/promotion-product-list.component';

@Component({
  selector: 'app-promotion-list',
  templateUrl: './promotion-list.component.html',
  styleUrls: ['./promotion-list.component.css'],
  providers: [PromotionService]
})
export class PromotionListComponent extends AbstractCRUDComponent<PromotionMeta> {

  constructor(
    service: PromotionService,
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
    return 'Quản lý khuyến mãi';
  }

  getCreateModalComponent(): any {
    return PromotionCreateComponent;
  }

  getEditModalComponent(): any {
    return PromotionEditComponent;
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
      status: new FormControl(null),
      type: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          name: 'Hoạt động',
          value: 1
        },
        {
          name: 'Không hoạt động',
          value: 0
        },
      ]),
      FieldForm.createSelect('Loại chương trình', 'type', 'Chọn một', [
        {
          name: 'Giảm giá sản phẩm',
          value: 1
        }, {
          name: 'Đồng giá',
          value: 2
        }, {
          name: 'FreeShip',
          value: 3
        }, {
          name: 'Giảm giá đơn hàng',
          value: 4
        }
      ]),
    ];
  }

  initNewModel(): PromotionMeta {
    return new PromotionMeta();
  }

  onStatusChange(item: PromotionMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Đã bật';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Đã tắt';
    }
    methodAsync.subscribe((res: PromotionMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createPromotion() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PromotionMeta> = <AbstractModalComponent<PromotionMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<PromotionMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editPromotion(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<PromotionMeta> = <AbstractModalComponent<PromotionMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<PromotionMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  viewProduct(item: PromotionMeta, i: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), {'class': 'modal-huge'});
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(PromotionProductListComponent, config);
    let modal: AbstractCRUDModalComponent<PromotionProductMeta> = <AbstractCRUDModalComponent<PromotionProductMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

}
