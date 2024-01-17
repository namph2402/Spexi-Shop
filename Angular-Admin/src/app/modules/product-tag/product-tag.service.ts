import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductTagMeta} from './product-tag.meta';
import {catchError, map} from 'rxjs/operators';
import {DataResponse} from '../../core';
import {Observable} from 'rxjs';

@Injectable()
export class ProductTagService extends AbstractCRUDService<ProductTagMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tag sản phẩm', 'product_tags');
  }

  attachTags(id: number, product_ids: number[]): Observable<ProductTagMeta> {
    return this.http.post<DataResponse<ProductTagMeta>>(`${this.urlRestAPI}/${id}/attach_tags`, {product_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  detachTags(id: number, product_ids: number[]): Observable<ProductTagMeta> {
    return this.http.post<DataResponse<ProductTagMeta>>(`${this.urlRestAPI}/${id}/detach_tags`, {product_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
