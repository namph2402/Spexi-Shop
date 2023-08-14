import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PostTagMeta} from './post-tag.meta';

@Injectable()
export class PostTagService extends AbstractCRUDService<PostTagMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tag bài viết', 'post_tags');
  }
}
