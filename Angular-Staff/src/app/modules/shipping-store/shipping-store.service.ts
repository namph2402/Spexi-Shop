import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {ShippingStoreMeta} from './shipping-store.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class ShippingStoreService extends AbstractCRUDService<ShippingStoreMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'cửa hàng', 'shipping_stores');
  }

}
