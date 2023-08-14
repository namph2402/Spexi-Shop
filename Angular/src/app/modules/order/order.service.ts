import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {OrderMeta} from './order.meta';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';
import { Observable } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class OrderService extends AbstractCRUDService<OrderMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'đơn hàng', 'orders');
  }

  confirm(item: OrderMeta): Observable<OrderMeta> {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item['id']}/confirm`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  cancel(item: OrderMeta): Observable<OrderMeta> {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item['id']}/cancel`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
