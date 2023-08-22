import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {VoucherMeta} from './voucher.meta';

@Injectable()
export class VoucherService extends AbstractCRUDService<VoucherMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'voucher', 'vouchers');
  }

}
