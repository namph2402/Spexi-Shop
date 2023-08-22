import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {FormDataMeta} from './form-data.meta';

@Injectable()
export class FormDataService extends AbstractCRUDService<FormDataMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'dữ liệu', 'form_datas');
  }

}
