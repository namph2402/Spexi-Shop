import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {WarehouseMeta} from '../warehouse.meta';
import {WarehouseService} from '../warehouse.service';
import {WarehouseEditComponent} from '../warehouse-edit/warehouse-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import {WarehouseImportComponent} from '../warehouse-import/warehouse-import.component';

@Component({
  selector: 'app-warehouse',
  templateUrl: './warehouse-list.component.html',
  styleUrls: ['./warehouse-list.component.css'],
  providers: [WarehouseService]
})
export class WarehouseListComponent extends AbstractCRUDComponent<WarehouseMeta> {

  constructor(
    service: WarehouseService,
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
    return 'Quản lý kho hàng';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return WarehouseEditComponent;
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
      status: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          name:'Hoạt động',
          value: '1'
        },
        {
          name:'Không hoạt động',
          value: '0'
        },
      ]),
    ];
  }

  initNewModel(): WarehouseMeta {
    return new WarehouseMeta();
  }

  onStatusChange(item: WarehouseMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: WarehouseMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  editWarehouse(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<WarehouseMeta> = <AbstractModalComponent<WarehouseMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<WarehouseMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  import() {
    const config = {ignoreBackdropClick: true};
    const modalRef = this.modalService.show(WarehouseImportComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }
}
