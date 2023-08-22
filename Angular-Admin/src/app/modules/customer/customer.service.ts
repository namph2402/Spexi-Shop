import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {CustomerMeta} from './customer.meta';

@Injectable()
export class CustomerService extends AbstractCRUDService<CustomerMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'khách hàng', 'users');
  }
}
