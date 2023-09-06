import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {OrderDetailMeta} from './order-detail.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class OrderDetailService extends AbstractCRUDService<OrderDetailMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'chi tiết đơn hàng', 'orders');
  }

}
