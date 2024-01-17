import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ShippingFeeMeta} from './shipping-fee.meta';
import {DataResponse} from '../../core';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ShippingFeeService extends AbstractCRUDService<ShippingFeeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'phí ship', 'shipping_fees');
  }

  truncate() {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/truncate`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
