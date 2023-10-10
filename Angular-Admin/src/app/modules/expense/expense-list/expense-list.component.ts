import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ExpenseMeta} from '../expense.meta';
import {ExpenseService} from '../expense.service';
import {ExpenseCreateComponent} from '../expense-create/expense-create.component';
import {ExpenseEditComponent} from '../expense-edit/expense-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import * as moment from 'moment';

@Component({
  selector: 'app-expense',
  templateUrl: './expense-list.component.html',
  styleUrls: ['./expense-list.component.css'],
  providers: [ExpenseService]
})
export class ExpenseListComponent extends AbstractCRUDComponent<ExpenseMeta> {

  year: any = [];

  constructor(
    service: ExpenseService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
    for (let i = 2100; i > 1900; i--) {
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
    return 'Quản lý giao dịch chi tiêu';
  }

  getCreateModalComponent(): any {
    return ExpenseCreateComponent;
  }

  getEditModalComponent(): any {
    return ExpenseEditComponent;
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
      month: new FormControl(moment(new Date().getTime()).format('MM')),
      year: new FormControl(moment(new Date().getTime()).format('YYYY')),
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

  initNewModel(): ExpenseMeta {
    return new ExpenseMeta();
  }

  createExpense() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ExpenseMeta> = <AbstractModalComponent<ExpenseMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<ExpenseMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editExpense(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ExpenseMeta> = <AbstractModalComponent<ExpenseMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ExpenseMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  confirm(item: ExpenseMeta) {
    (<ExpenseService>this.service).confirm(item.id).subscribe(res => {
      this.service.toastSuccessfully('Duyệt');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
}
