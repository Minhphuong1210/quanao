<?php

class PaymentStatus
{
    const UNPAID = 'unpaid';   // chưa thanh toán
    const PAID   = 'paid';     // đã thanh toán

    public static function all()
    {
        return [
            self::UNPAID,
            self::PAID,
        ];
    }
}
