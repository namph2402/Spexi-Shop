import {MenuPositionMeta} from '../menu-position/menu-position.meta';

export class MenuMeta {
  id: number;
  group_id: number;
  parent_id: number;
  name: number;
  url: string;
  order: number;
  status: boolean;
  parent: MenuPositionMeta;
}
