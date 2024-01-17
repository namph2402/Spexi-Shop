import {PostMeta} from '../post/post.meta';

export class PostRelatedMeta {
  id: number;
  post_id: number;
  related_id: number;
  order: number;
  post: PostMeta;
}
