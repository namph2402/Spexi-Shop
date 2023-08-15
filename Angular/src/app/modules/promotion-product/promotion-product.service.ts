import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PromotionProductMeta} from './promotion-product.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {ProductMeta} from '../product/product.meta';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class PromotionProductService extends AbstractCRUDService<PromotionProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'khuyến mại', 'promotions');
  }

  loadProduct(id: number, params) {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.toPipe(this.http.get<DataResponse<PromotionProductMeta>>(`${this.urlRestAPI}/${id}/loadProduct`, {params: parameters}));
  }

  updateSalePrice(id: number, body: any): Observable<ProductMeta> {
    return this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${id}/updateSalePrice`, body)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  assignProducts(id: number, items: any): Observable<ProductMeta> {
    return this.http.post<DataResponse<PromotionProductMeta>>(`${this.urlRestAPI}/${id}/attach_products`, {items: items})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  detachProduct(promotion_id: number, body: any,) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${promotion_id}/detach_products`, body));
  }
}
