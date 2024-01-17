import {OrderDetailMeta} from '../order-detail/order-detail.meta';
import {OrderShipMeta} from '../order-ship/order-ship.meta';
import {VoucherMeta} from '../voucher/voucher.meta';

export class OrderMeta {
  public static $LEN_DON = 'Lên đơn';
  public static $XAC_NHAN = 'Xác nhận';
  public static $CHUAN_BI_HANG = 'Chuẩn bị hàng';
  public static $DA_BI_HANG = 'Đã chuẩn bị hàng';
  public static $DANG_GIAO = 'Đang giao';
  public static $DA_GIAO = 'Đã giao';
  public static $HOAN_THANH = 'Hoàn thành';
  public static $HUY_DON = 'Hủy đơn';

  id: number;
  code: string;
  user_id: number;
  customer_name: string;
  customer_phone: number;
  customer_address: boolean;
  customer_request: string;
  customer_text: string;
  province: string;
  district: string;
  ward: string;
  amount: number;
  shipping_fee: number;
  total_amount: number;
  cod_fee: number;
  note: string;
  discount: number;
  voucher_id: number;
  payment_type: string;
  payment_status: string;
  order_status: string;
  is_completed: number;
  created_at: string;
  updated_at: string;
  voucher: VoucherMeta;
  details: OrderDetailMeta[];
  shipping: OrderShipMeta;
}

