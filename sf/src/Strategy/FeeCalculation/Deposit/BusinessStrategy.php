<?php

declare(strict_types=1);

namespace App\Strategy\FeeCalculation\Deposit;

use App\Model\Money;
use App\Model\Transaction;
use App\Strategy\CalculationStrategyInterface;
use App\Strategy\FeeCalculation\AbstractFeeCalculationStrategy;

/**
 * Class BusinessStrategy
 * Description:
 * All deposits are charged 0.03% of deposit amount.
 * @package App\Strategy\FeeCalculation\Deposit
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
