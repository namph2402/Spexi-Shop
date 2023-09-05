import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {ShippingServiceMeta} from './shipping-service.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class ShippingServiceService extends AbstractCRUDService<ShippingServiceMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'dịch vụ', 'shipping_services');
  }

}
