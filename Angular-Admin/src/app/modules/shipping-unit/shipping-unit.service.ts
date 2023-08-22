import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {ShippingUnitMeta} from './shipping-unit.meta';
import { Observable } from 'rxjs';
import { DataResponse } from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ShippingUnitService extends AbstractCRUDService<ShippingUnitMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'đối tác vận chuyển', 'shipping_units');
  }

  sync(id: number): Observable<ShippingUnitMeta> {
    return this.http.post<DataResponse<ShippingUnitMeta>>(`${this.urlRestAPI}/${id}/synchronized`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  createUnitPartner(item: Object): Observable<ShippingUnitMeta> {
    return this.http.post<DataResponse<ShippingUnitMeta>>(`${this.urlRestAPI}/createUnitPartner`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
