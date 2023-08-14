import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PostRelatedMeta} from './post-related.meta';
import {Observable} from 'rxjs';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class PostRelatedService extends AbstractCRUDService<PostRelatedMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'bài viết liên quan', 'related_posts');
  }

  addRelated(data: any): Observable<PostRelatedMeta> {
    return this.http.post<DataResponse<PostRelatedMeta>>(`${this.urlRestAPI}`, data)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadPosts(params) {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.toPipe(this.http.get<DataResponse<PostRelatedMeta>>(`${this.urlRestAPI}/loadPost`, {params: parameters}));
  }

}
