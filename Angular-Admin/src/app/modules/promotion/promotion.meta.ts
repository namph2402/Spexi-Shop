import {ProductMeta} from '../product/product.meta';

export class PromotionMeta {

  public static $GIAM_SAN_PHAM = '1';
  public static $DONG_GIA = '2';
  public static $FREE_SHIP = '3';
  public static $GIAM_DON_HANG = '4';

  id: number;
  name: string;
  image: string;
  min_products_count: number;
  min_order_value: number;
  discount_value: number;
  discount_percent: number;
  discount_same: number;
  expired_date: string;
  type: number;
  status: boolean;
  full_path: string;
  products: ProductMeta;
}
