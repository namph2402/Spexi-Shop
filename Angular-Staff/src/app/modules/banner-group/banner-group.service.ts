import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {BannerGroupMeta} from './banner-group.meta';

@Injectable()
export class BannerGroupService extends AbstractCRUDService<BannerGroupMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'nhóm banner', 'banner_groups');
  }
}
