import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {MenuPositionMeta} from './menu-position.meta';

@Injectable()
export class MenuPositionService extends AbstractCRUDService<MenuPositionMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Nhóm menu', 'menu_groups');
  }

}
