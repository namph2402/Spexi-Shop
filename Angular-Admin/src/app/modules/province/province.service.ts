import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';
import {ProvinceMeta} from './province.meta';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class ProvinceService extends AbstractCRUDService<ProvinceMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'địa chỉ', 'provinces');
  }

  truncate() {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/truncate`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
