import {ShippingStoreMeta} from '../shipping-store/shipping-store.meta';
import {ShippingServiceMeta} from '../shipping-service/shipping-service.meta';

export class ShippingUnitMeta {
  id: number;
  name: string;
  logo: string;
  priovity: number;
  user_id: number;
  shipping_strores: ShippingStoreMeta[];
  shipping_services: ShippingServiceMeta[];

}
