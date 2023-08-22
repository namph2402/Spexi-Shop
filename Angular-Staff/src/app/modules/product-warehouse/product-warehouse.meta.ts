import { ProductColorMeta } from "../product-color/product-color.meta";
import { ProductSizeMeta } from "../product-size/product-size.meta";

export class ProductWarehouseMeta {
  id: number;
  product_id: number;
  code: string;
  size_id: number;
  color_id: number;
  weight: number;
  quantity: number;
  use_quantity: number;
  sizes: ProductSizeMeta;
  colors:ProductColorMeta
}
