import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PromotionMeta} from './promotion.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {ProductMeta} from '../product/product.meta';

@Injectable()
export class PromotionService extends AbstractCRUDService<PromotionMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'khuyến mãi', 'promotions');
  }

  assignProducts(id: number, ids: number[]): Observable<ProductMeta> {
    return this.toPipe(this.http.post<DataResponse<PromotionMeta>>(`${this.urlRestAPI}/${id}/attach_products?product_ids=${ids}`, {ids: ids.join()}));
  }

  css(object: PromotionMeta): Observable<PromotionMeta> {
    return this.toPipe(this.http.put<DataResponse<PromotionMeta>>(`${this.urlRestAPI}/${object['id']}/css`, {css: object['css']}));
  }

  script(object: PromotionMeta): Observable<PromotionMeta> {
    return this.toPipe(this.http.put<DataResponse<PromotionMeta>>(`${this.urlRestAPI}/${object['id']}/script`, {script: object['script']}));
  }
}
