import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductColorMeta} from './product-color.meta';

@Injectable()
export class ProductColorService extends AbstractCRUDService<ProductColorMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'thuộc tính', 'product_colors');
  }

}
