export class VoucherMeta {

  public static $GIAM_DON_HANG = '1';
  public static $FREE_SHIP = '2';

  id: number;
  name: string;
  code: string;
  type: number;
  quantity: number;
  remain_quantity: number;
  min_order_value: number;
  discount_value: number;
  discount_percent: number;
  expired_date: string;
  private: boolean;
  status: boolean;
}
