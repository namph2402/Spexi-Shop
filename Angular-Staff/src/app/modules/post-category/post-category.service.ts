import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PostCategoryMeta} from './post-category.meta';

@Injectable()
export class PostCategoryService extends AbstractCRUDService<PostCategoryMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'danh mục bài đăng', 'post_categories');
  }
}
