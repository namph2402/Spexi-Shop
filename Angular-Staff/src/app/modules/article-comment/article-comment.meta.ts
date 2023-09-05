import { UserMeta } from "../user/user.meta";

export class ArticleCommentMeta {
  id: number;
  parent_id: number;
  content: string;
  author_id: number;
  approved: boolean;
  article_id: number;
  rating: number;
  status: boolean;
  created_at: string;
  author:UserMeta;
}
