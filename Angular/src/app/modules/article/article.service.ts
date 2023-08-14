import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {ArticleMeta} from './article.meta';
import {TitleService} from '../../core/services';

@Injectable()
export class ArticleService extends AbstractCRUDService<ArticleMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'bài viết', 'articles');
  }
}
