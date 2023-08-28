import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PaymentMethodMeta} from './payment-method.meta';
import { Observable } from 'rxjs';
import { DataResponse } from '../../core/common';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class PaymentMethodService extends AbstractCRUDService<PaymentMethodMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thanh toán', 'payment_methods');
  }

  destroyConfig(id: number): Observable<PaymentMethodMeta> {
    return this.http.post<DataResponse<PaymentMethodMeta>>(`${this.urlRestAPI}/${id}/destroyConfig`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  destroy(id: number): Observable<PaymentMethodMeta> {
    return this.http.post<DataResponse<PaymentMethodMeta>>(`${this.urlRestAPI}/${id}/destroyPaymentMethod`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
