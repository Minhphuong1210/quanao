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

    public static function next($current)
    {
        $flow = [
            self::PENDING   => self::CONFIRMED,
            self::CONFIRMED => self::SHIPPING,
            self::SHIPPING  => self::COMPLETED,
        ];

        return $flow[$current] ?? null;
    }

    public static function allowedNextStatuses($current)
    {
        $result = [];

        $next = self::next($current);
        if ($next) {
            $result[] = $next;
        }

        if (in_array($current, [self::PENDING, self::CONFIRMED])) {
            $result[] = self::CANCELLED;
        }

        return $result;
    }

    public static function isFinal($status)
    {
        return in_array($status, [
            self::COMPLETED,
            self::CANCELLED
        ]);
    }


    public static function canChange($current, $new)
    {
        return in_array($new, self::allowedNextStatuses($current));
    }
    public static function label($status)
    {
        switch ($status) {
            case self::PENDING:
                return 'Chờ xử lý';
    
            case self::CONFIRMED:
                return 'Đã xác nhận';
    
            case self::SHIPPING:
                return 'Đang giao';
    
            case self::COMPLETED:
                return 'Hoàn thành';
    
            case self::CANCELLED:
                return 'Đã huỷ';
    
            default:
                return 'Không xác định';
        }
    }
    


}
