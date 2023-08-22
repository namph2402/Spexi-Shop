import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {WarehouseMeta} from '../warehouse.meta';
import {WarehouseService} from '../warehouse.service';
import {WarehouseCreateComponent} from '../warehouse-create/warehouse-create.component';
import {WarehouseEditComponent} from '../warehouse-edit/warehouse-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';

@Component({
  selector: 'app-Warehouse',
  templateUrl: './Warehouse-list.component.html',
  styleUrls: ['./Warehouse-list.component.css'],
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
    return 'Quản lý nhân viên';
  }

  getCreateModalComponent(): any {
    return WarehouseCreateComponent;
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
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          name:'Tất cả',
          value: 'all'
        },
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

  createWarehouse() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<WarehouseMeta> = <AbstractModalComponent<WarehouseMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<WarehouseMeta>) => {
      if (result.success) {
        this.load();
      }
    });
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
}
