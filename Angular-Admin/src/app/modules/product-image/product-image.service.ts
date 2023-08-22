import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductImageMeta} from './product-image.meta';

@Injectable()
export class ProductImageService extends AbstractCRUDService<ProductImageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'ảnh sản phẩm', 'product_images');
  }

}
