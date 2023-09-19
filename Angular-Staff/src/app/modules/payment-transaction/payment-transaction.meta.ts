import { OrderMeta } from "../order/order.meta";

export class PaymentTransactionMeta {
  id: number;
  name: string;
  order_id: number;
  order_code: string;
  creator_name: string;
  amount: number;
  method: string;
  status: string;
  massage: string;
  dump_data: string;
  order: OrderMeta;
}
