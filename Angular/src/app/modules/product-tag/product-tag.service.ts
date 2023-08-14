import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductTagMeta} from './product-tag.meta';

@Injectable()
export class ProductTagService extends AbstractCRUDService<ProductTagMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tag sản phẩm', 'product_tags');
  }
}
