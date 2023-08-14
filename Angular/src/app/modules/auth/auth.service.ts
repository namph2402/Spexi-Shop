import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {AuthMeta} from './auth.meta';


@Injectable()
export class AuthService extends AbstractCRUDService<AuthMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Xác thực tài khoản', 'login');
  }

}
