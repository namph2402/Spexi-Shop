import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {WardMeta} from './ward.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class WardService extends AbstractCRUDService<WardMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Xã/Phường', 'wards');
  }

}
