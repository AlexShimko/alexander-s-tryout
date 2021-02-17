<?php

declare(strict_types=1);

namespace App\Strategy\FeeCalculation\Withdraw;

use App\Model\Money;
use App\Model\Transaction;
use App\Strategy\CalculationStrategyInterface;
use App\Strategy\FeeCalculation\AbstractFeeCalculationStrategy;

/**
 * Class BusinessStrategy
 * Description:
 * Commission fee - 0.5% from withdrawn amount.
 * @package App\Strategy\FeeCalculation\Withdraw
 */
class BusinessStrategy extends AbstractFeeCalculationStrategy implements CalculationStrategyInterface
{
    /**
     * @param Transaction $transaction
     * @return Money
     */
    public function calculate(Transaction $transaction): Money
    {
        $feeAmount = $transaction->getOperationAmount()->percent($this->getFeePercent($transaction));
        $transaction->setFeeAmount($feeAmount);

        return $feeAmount;
    }
}
