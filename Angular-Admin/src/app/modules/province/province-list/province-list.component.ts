import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ProvinceMeta} from '../province.meta';
import {ProvinceService} from '../province.service';
import {AppPagination, FieldForm, ModalResult} from '../../../core/common';
import {ProvinceImportComponent} from '../province-import/province-import.component';
import { WardService } from '../../ward/ward.service';
import { ObjectUtil } from '../../../core';

@Component({
  selector: 'app-province',
  templateUrl: './province-list.component.html',
  styleUrls: ['./province-list.component.css'],
  providers: [ProvinceService, WardService]
})
export class ProvinceListComponent extends AbstractCRUDComponent<ProvinceMeta> {

  constructor(
    service: ProvinceService,
    modal: BsModalService,
    builder: FormBuilder,
    wardService: WardService
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý địa chỉ';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
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
    ];
  }

  initNewModel(): ProvinceMeta {
    return new ProvinceMeta();
  }

  load() {
    let params: any = ObjectUtil.combineValue({
      type: 'ward',
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
      }, () => {
        console.log(123)
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  import() {
    const config = {ignoreBackdropClick: true};
    const modalRef = this.modalService.show(ProvinceImportComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  truncate() {
    (<ProvinceService>this.service).truncate().subscribe(res => {
      this.service.toastSuccessfully('Xóa bỏ');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
}
