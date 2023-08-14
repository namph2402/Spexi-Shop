import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import {ProvinceMeta} from './province.meta';

@Injectable()
export class ProvinceService extends AbstractCRUDService<ProvinceMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý kênh', 'provinces');
  }

}
