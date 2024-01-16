import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {DashboardService} from './dashboard.service';
import {FieldForm, ObjectUtil} from '../../core';
import * as moment from 'moment';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
  providers: [DashboardService]
})
export class DashboardComponent extends AbstractCRUDComponent<any> {

  boxes: any[];
  products: any[];
  orders: any[];
  userMains: any[];
  year: any = [];

  constructor(
    service: DashboardService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
    let year = Number(moment(new Date().getTime()).format('YYYY'));
    for (let i = year; i >= 2000; i--) {
      i
      let data = {
        'name' : i ,
        'value' : i
      }
      this.year.push(data)
    }
    this.searchControls[1].data = this.year;
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  getTitle(): string {
    return 'Bảng điều khiển';
  }

  initNewModel(): any {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      month: new FormControl(moment(new Date().getTime()).format('MM')),
      year: new FormControl(moment(new Date().getTime()).format('YYYY')),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
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

  load(): void {
    let param: any = ObjectUtil.combineValue({}, this.searchForm.value, true);
    this.service.loadByParams(param).subscribe(val => {
      this.boxes = val['boxes'];
      this.products = val['products'];
      this.orders = val['orders'];
      this.userMains = val['user'];
    });
  }
}
