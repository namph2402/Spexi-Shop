import {BannerMeta} from '../banner/banner.meta';

export class BannerGroupMeta {
  id: number;
  name: string;
  code: string;
  parent_id: number;
  parent: BannerGroupMeta;
  banners: BannerMeta[];
}
