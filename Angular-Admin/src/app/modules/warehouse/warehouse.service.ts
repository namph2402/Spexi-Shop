import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {WarehouseMeta} from './warehouse.meta';

@Injectable()
export class WarehouseService extends AbstractCRUDService<WarehouseMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'kho hàng', 'warehouses');
  }

}
