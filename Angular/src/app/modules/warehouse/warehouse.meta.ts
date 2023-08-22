import { ProductColorMeta } from "../product-color/product-color.meta";
import { ProductSizeMeta } from "../product-size/product-size.meta";
import { ProductMeta } from "../product/product.meta";

export class WarehouseMeta {
  id: number;
  product_id: number;
  code: string;
  size_id: number;
  color_id: number;
  weight: number;
  quantity: number;
  use_quantity: number;
  product: ProductMeta
  sizes: ProductSizeMeta;
  colors:ProductColorMeta
}
