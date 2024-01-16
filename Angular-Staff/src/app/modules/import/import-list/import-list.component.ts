import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ImportMeta} from '../import.meta';
import {ImportService} from '../import.service';
import {FieldForm, ModalResult} from '../../../core/common';
import { ImportDetailListComponent } from '../import-detail-list/import-detail-list.component';
import { ImportDetailMeta } from '../import-detail.meta';
import * as moment from 'moment';

@Component({
  selector: 'app-import',
  templateUrl: './import-list.component.html',
  styleUrls: ['./import-list.component.css'],
  providers: [ImportService]
})
export class ImportListComponent extends AbstractCRUDComponent<ImportMeta> {

  year: any = [];

  constructor(
    service: ImportService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
    let year = Number(moment(new Date().getTime()).format('YYYY'));
    for (let i = year; i >= 2000; i--) {
      i
      let data = {
        'name' : i ,
        'value' : i
      }
      this.year.push(data)
    }
    this.searchControls[2].data = this.year;
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Phiếu nhập kho';
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
      month: new FormControl(null),
      year: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm tháng', 'month', 'Tháng', [
        {
          name:'Tháng 1',
          value:'01',
        },
        {
          name:'Tháng 2',
          value:'02',
        },
        {
          name:'Tháng 3',
          value:'03',
        },
        {
          name:'Tháng 4',
          value:'04',
        },
        {
          name:'Tháng 5',
          value:'05',
        },
        {
          name:'Tháng 6',
          value:'06',
        },
        {
          name:'Tháng 7',
          value:'07',
        },
        {
          name:'Tháng 8',
          value:'08',
        },
        {
          name:'Tháng 9',
          value:'09',
        },
        {
          name:'Tháng 10',
          value:'10',
        },
        {
          name:'Tháng 11',
          value:'11',
        },
        {
          name:'Tháng 12',
          value:'12',
        },
      ]),
      FieldForm.createSelect('Tìm kiếm năm', 'year', 'Năm', []),
    ];
  }

  initNewModel(): ImportMeta {
    return new ImportMeta();
  }

  showDetail(item: ImportMeta) {
    let modalRef = this.modalService.show(ImportDetailListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ImportDetailMeta> = <AbstractCRUDModalComponent<ImportDetailMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ImportDetailMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

}
