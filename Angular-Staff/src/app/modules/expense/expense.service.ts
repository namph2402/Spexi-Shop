import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ExpenseMeta} from './expense.meta';
import { DataResponse } from '../../core';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class ExpenseService extends AbstractCRUDService<ExpenseMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'giao dịch chi tiêu', 'expenses');
  }

  confirm(id: number) {
    return this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${id}/confirm`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
