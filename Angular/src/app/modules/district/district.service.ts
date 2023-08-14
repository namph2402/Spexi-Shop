import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {DistrictMeta} from './district.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class DistrictService extends AbstractCRUDService<DistrictMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quận/Huyện', 'districts');
  }
}
