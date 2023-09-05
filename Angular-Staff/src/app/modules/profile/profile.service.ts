import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProfileMeta} from './profile.meta';
import {Observable} from 'rxjs';
import {DataResponse} from '../../core';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ProfileService extends AbstractCRUDService<ProfileMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thông tin cá nhân', 'profile');
  }

  update(data: ProfileMeta): Observable<ProfileMeta> {
    return this.http.put<DataResponse<ProfileMeta>>(`${this.urlRestAPI}`, data)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  repassword(): Observable<ProfileMeta> {
    return this.http.put<DataResponse<ProfileMeta>>(`${this.urlRestAPI}/repassword`, [])
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
