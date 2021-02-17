<?php

declare(strict_types=1);

namespace App\Strategy;

use App\Model\Money;
use App\Model\Transaction;

/**
 * Interface CalculationStrategyInterface
 * @package App\Strategy
 */
interface CalculationStrategyInterface
{
    public function calculate(Transaction $transaction): Money;
}
