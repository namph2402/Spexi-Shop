import {ProductMeta} from '../product/product.meta';

export class CartItemMeta {
  product: ProductMeta;
  warehouse_id: number;
  unit_price: number;
  quantity: number;
}
