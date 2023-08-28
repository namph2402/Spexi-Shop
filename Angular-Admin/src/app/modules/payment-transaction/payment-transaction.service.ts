import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PaymentTransactionMeta} from './payment-transaction.meta';

@Injectable()
export class PaymentTransactionService extends AbstractCRUDService<PaymentTransactionMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'giao dịch', 'payment_transactions');
  }

}
