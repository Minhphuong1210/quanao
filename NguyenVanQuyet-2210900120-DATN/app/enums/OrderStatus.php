<?php

class OrderStatus
{
    const PENDING   = 'pending';    // chờ xử lý
    const CONFIRMED = 'confirmed';  // đã xác nhận
    const SHIPPING  = 'shipping';   // đang giao
    const COMPLETED = 'completed';  // hoàn thành
    const CANCELLED = 'cancelled';  // đã huỷ

    public static function all()
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::SHIPPING,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
}
