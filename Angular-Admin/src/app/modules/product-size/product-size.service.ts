import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductSizeMeta} from './product-size.meta';

@Injectable()
export class ProductSizeService extends AbstractCRUDService<ProductSizeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'thuộc tính', 'product_sizes');
  }

}
