import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StoreMeta} from './store.meta';

@Injectable()
export class StoreService extends AbstractCRUDService<StoreMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'thông tin cửa hàng', 'stores');
  }
}
