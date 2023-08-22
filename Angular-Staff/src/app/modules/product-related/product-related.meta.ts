import { ProductMeta } from "../product/product.meta";

export class ProductRelatedMeta {
  id: number;
  product_id: number;
  related_id: number;
  order: number;
  product: ProductMeta
}
