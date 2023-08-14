import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PostMeta} from './post.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class PostService extends AbstractCRUDService<PostMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'bài viết', 'posts');
  }

  attachTags(id: number, tag_ids: number[]): Observable<PostMeta> {
    return this.http.post<DataResponse<PostMeta>>(`${this.urlRestAPI}/${id}/attach_tags`, {tag_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  detachTags(id: number, tag_ids: number[]): Observable<PostMeta> {
    return this.http.post<DataResponse<PostMeta>>(`${this.urlRestAPI}/${id}/detach_tags`, {tag_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
