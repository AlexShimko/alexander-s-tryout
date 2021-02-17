<?php

declare(strict_types=1);

namespace App\Strategy\FeeCalculation;

use App\Calculator\WeeklyLimitCalculator;
use App\Configuration\FeeCommissionConfig;
use App\Model\Transaction;

/**
 * Class AbstractFeeCalculationStrategy
 * @package App\Strategy\FeeCalculation
 */
abstract class AbstractFeeCalculationStrategy
{
    /**
     * @var WeeklyLimitCalculator
     */
    protected WeeklyLimitCalculator $weeklyLimitCalculator;

    /**
     * @var FeeCommissionConfig
     */
    protected FeeCommissionConfig $feeCommissionConfig;

    /**
     * AbstractFeeCalculationStrategy constructor.
     * @param WeeklyLimitCalculator $weeklyLimitCalculator
     * @param FeeCommissionConfig $feeCommissionConfig
     */
    public function __construct(
        WeeklyLimitCalculator $weeklyLimitCalculator,
        FeeCommissionConfig $feeCommissionConfig
    ) {
        $this->weeklyLimitCalculator = $weeklyLimitCalculator;
        $this->feeCommissionConfig = $feeCommissionConfig;
    }

    /**
     * Get fee percent from config by operation type and client type
     *
     * @param Transaction $transaction
     * @return float
     */
    public function getFeePercent(Transaction $transaction): float
    {
        return $this->feeCommissionConfig->getFeePercent(
            $transaction->getOperationType(),
            $transaction->getClient()->getClientType()
        );
    }

    /**
     * Dependency required for Unit-test
     *
     * @return WeeklyLimitCalculator
     */
    public function getWeeklyLimitCalculator(): WeeklyLimitCalculator
    {
        return $this->weeklyLimitCalculator;
    }
}
