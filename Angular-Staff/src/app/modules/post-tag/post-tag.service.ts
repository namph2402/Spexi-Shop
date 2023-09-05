import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PostTagMeta} from './post-tag.meta';
import { Observable } from 'rxjs';
import { DataResponse } from '../../core';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class PostTagService extends AbstractCRUDService<PostTagMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tag bài viết', 'post_tags');
  }

  attachTags(id: number, post_ids: number[]): Observable<PostTagMeta> {
    return this.http.post<DataResponse<PostTagMeta>>(`${this.urlRestAPI}/${id}/attach_tags`, {post_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  detachTags(id: number, post_ids: number[]): Observable<PostTagMeta> {
    return this.http.post<DataResponse<PostTagMeta>>(`${this.urlRestAPI}/${id}/detach_tags`, {post_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
