import {ShippingUnitMeta} from '../shipping-unit/shipping-unit.meta';

export class OrderShipMeta {
  id: number;
  order_id: any;
  deliver_id: number;
  code: string;
  status: string;
  status_id: number;
  total_fee: number;
  expected_delivery_time: string;
  note: string;
  is_printed: boolean;
  unit: ShippingUnitMeta;
}
