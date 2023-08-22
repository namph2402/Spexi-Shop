export class ProductCategoryMeta {
  id: number;
  name: string;
  full_path: string;
  slug: string;
  image: string;
  order: number;
  status: boolean;
  parent: ProductCategoryMeta;
}
