import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {OrderMeta} from './order.meta';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';

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

  refund(item: OrderMeta): Observable<OrderMeta> {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item['id']}/refund`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  prepare(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/prepare`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  return(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/return`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  returned(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/returned`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
