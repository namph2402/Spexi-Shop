import {ArticleMeta} from '../article/article.meta';
import {ProductCategoryMeta} from '../product-category/product-category.meta';
import { ProductVariantMeta } from '../product-variant/product-variant.meta';
import {PromotionMeta} from '../promotion/promotion.meta';

export class ProductMeta {
  id: number;
  category_id: number;
  name: string;
  code: string;
  slug: string;
  image: string;
  summary: string;
  sale_price: number;
  price: number;
  order: number;
  status: boolean;
  category: ProductCategoryMeta;
  article: ArticleMeta;
  promotions: PromotionMeta;
  warehouses: ProductVariantMeta
}
