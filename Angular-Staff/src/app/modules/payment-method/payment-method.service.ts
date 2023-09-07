import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PaymentMethodMeta} from './payment-method.meta';

@Injectable()
export class PaymentMethodService extends AbstractCRUDService<PaymentMethodMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thanh toán', 'payment_methods');
  }
}
