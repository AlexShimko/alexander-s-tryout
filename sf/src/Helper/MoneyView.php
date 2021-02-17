<?php

declare(strict_types=1);

namespace App\Helper;

use App\Model\Money;

/**
 * Class MoneyView represent conversion method from int to float divided by 100 for proper money values represent
 * @package App\Helper
 */
class MoneyView
{
    /**
     * @param Money $money
     * @return string
     */
    public function __invoke(Money $money): string
    {
        return \sprintf('%01.2f', $money->getAmount() / 100);
    }
}
