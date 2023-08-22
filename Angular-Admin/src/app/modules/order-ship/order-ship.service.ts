import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {OrderShipMeta} from './order-ship.meta';
import {catchError, map} from 'rxjs/operators';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';

@Injectable()
export class OrderShipService extends AbstractCRUDService<OrderShipMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'vận đơn', 'order_ships');
  }

  printBills(ids: number[]) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/printBills`, {params: {order_ids: ids.join(',')}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  printBill(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/printBill`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  shipping(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/shipping`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  complete(id: number) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/complete`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
