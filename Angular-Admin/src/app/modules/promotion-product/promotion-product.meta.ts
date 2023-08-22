import {ProductMeta} from '../product/product.meta';

export class PromotionProductMeta {
  promotion_id: number;
  product_id: number;
  products: ProductMeta[];
  existsProducts: PromotionProductMeta[];
}
