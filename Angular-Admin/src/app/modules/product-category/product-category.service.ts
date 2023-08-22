import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductCategoryMeta} from './product-category.meta';

@Injectable()
export class ProductCategoryService extends AbstractCRUDService<ProductCategoryMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'danh mục sản phẩm', 'product_categories');
  }

}
