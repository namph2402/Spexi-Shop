import {ArticleMeta} from '../article/article.meta';

export class PostMeta {
  id: number;
  category_id: number;
  category_slug: string;
  name: string;
  slug: string;
  summary: string;
  image: string;
  order: number;
  status: boolean;
  full_path: string;
  article: ArticleMeta;
}
