import {ProvinceMeta} from '../province/province.meta';
import {DistrictMeta} from '../district/district.meta';
import {WardMeta} from '../ward/ward.meta';

export class ShippingFeeMeta {
  id: number;
  province_id: number;
  province: ProvinceMeta;
  district_id: number;
  district: DistrictMeta;
  ward_id: number;
  ward: WardMeta;
  fee: number;
}
