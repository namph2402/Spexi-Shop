import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {NotificationMeta} from './notification.meta';

@Injectable()
export class NotificationService extends AbstractCRUDService<NotificationMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'thông báo', 'notifications');
  }
}
