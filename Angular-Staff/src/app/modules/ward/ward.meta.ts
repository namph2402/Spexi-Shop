import {DistrictMeta} from '../district/district.meta';

export class WardMeta {
  id: number;
  district_id: number;
  name: string;
  district: DistrictMeta;
}
