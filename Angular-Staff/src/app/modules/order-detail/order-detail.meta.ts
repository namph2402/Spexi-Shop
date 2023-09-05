import {ProductMeta} from '../product/product.meta';

export class OrderDetailMeta {
  id: number;
  order_id: number;
  product_id: number;
  product_name: string;
  warehouse_id: number;
  size: string;
  color: string;
  unit_price: number;
  quantity: number;
  amount: number;
  product: ProductMeta;
}
