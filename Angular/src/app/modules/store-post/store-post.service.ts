import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StorePostMeta} from './store-post.meta';

@Injectable()
export class StorePostService extends AbstractCRUDService<StorePostMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'bài viết cửa hàng', 'store_posts');
  }
}
