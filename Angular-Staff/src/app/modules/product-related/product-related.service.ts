import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductRelatedMeta} from './product-related.meta';
import {Observable} from 'rxjs';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ProductRelatedService extends AbstractCRUDService<ProductRelatedMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'sản phẩm liên quan', 'related_products');
  }
  
  addRelated(data: any): Observable<ProductRelatedMeta> {
    return this.http.post<DataResponse<ProductRelatedMeta>>(`${this.urlRestAPI}`, data)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadProducts(params) {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.toPipe(this.http.get<DataResponse<ProductRelatedMeta>>(`${this.urlRestAPI}/loadProduct`, {params: parameters}));
  }

}
