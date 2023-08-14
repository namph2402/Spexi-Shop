import {ProvinceMeta} from '../province/province.meta';
import {WardMeta} from '../ward/ward.meta';

export class DistrictMeta {
  id: number;
  province_id: number;
  name: string;
  wards: WardMeta[];
  province: ProvinceMeta;
}
