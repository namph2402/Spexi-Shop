import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StaffMeta} from './staff.meta';
import {DataResponse} from '../../core';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class StaffService extends AbstractCRUDService<StaffMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'nhân viên', 'staffs');
  }

  repassword(id: number) {
    return this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${id}/repassword`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
