<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Class CurrencyEnum
 * @package App\Enum
 */
class CurrencyEnum
{
    public const EUR = 'EUR';

    /**
     * @return string
     */
    public static function getDefaultCurrency(): string
    {
        return self::EUR;
    }
}
