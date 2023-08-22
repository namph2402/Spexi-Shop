import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services/';
import {ArticleCommentMeta} from './article-comment.meta';

@Injectable()
export class ArticleCommentService extends AbstractCRUDService<ArticleCommentMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'bình luận', 'comments');
  }
}
