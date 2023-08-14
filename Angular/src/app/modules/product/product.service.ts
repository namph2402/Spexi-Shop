import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductMeta} from './product.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ProductService extends AbstractCRUDService<ProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'sản phẩm', 'products');
  }

  attachTags(id: number, tag_ids: number[]): Observable<ProductMeta> {
    return this.http.post<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/attach_tags`, {tag_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  detachTags(id: number, tag_ids: number[]): Observable<ProductMeta> {
    return this.http.post<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/detach_tags`, {tag_ids})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadAvailableProducts(id: number, params) {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.toPipe(this.http.get<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/loadAvailableProducts`, {params: parameters}));
  }

}
