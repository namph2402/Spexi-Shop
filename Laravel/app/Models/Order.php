<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public static $LEN_DON = "Lên đơn";
    public static $XAC_NHAN = "Xác nhận";
    public static $CHUAN_BI_HANG = "Chuẩn bị hàng";
    public static $DA_CHUAN_BI_HANG = "Đã chuẩn bị hàng";
    public static $DANG_GIAO = "Đang giao";
    public static $DA_GIAO = "Đã giao";
    public static $HOAN_THANH = "Hoàn thành";
    public static $HUY_DON = "Hủy đơn";
    public static $HUY_GIAO = "Hủy giao hàng";
    public static $HOAN_HANG = "Hoàn hàng";
    public static $DA_HOAN_HANG = "Đã hoàn hàng";
    public static $DA_HOAN_TIEN = "Đã hoàn tiền";

    protected $guarded = [];
    protected $appends = [
        'customer_text',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCustomerTextAttribute()
    {
        return join("\n", [
            join(', ', [$this->attributes['customer_address'], $this->ward, $this->district, $this->province]),
        ]);
    }

    public function shipping()
    {
        return $this->hasOne(OrderShip::class, 'order_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}
