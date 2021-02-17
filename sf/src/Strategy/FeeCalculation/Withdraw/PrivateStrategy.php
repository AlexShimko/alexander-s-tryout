<?php

declare(strict_types=1);

namespace App\Strategy\FeeCalculation\Withdraw;

use App\Cache\TransactionDataCache;
use App\Calculator\WeeklyLimitCalculator;
use App\Configuration\FeeCommissionConfig;
use App\Model\Money;
use App\Model\Transaction;
use App\Strategy\CalculationStrategyInterface;
use App\Strategy\FeeCalculation\AbstractFeeCalculationStrategy;

/**
 * Class PrivateStrategy
 * Description:
 * 1000.00 EUR for a week (from Monday to Sunday) is free of charge.
 * Only for the first 3 withdraw operations per a week.
 * 4th and the following operations are calculated by using the rule above (0.3%).
 * If total free of charge amount is exceeded them commission is calculated only for the exceeded amount
 * (i.e. up to 1000.00 EUR no commission fee is applied).
 *
 * Note: this calculation strategy is not optimal for small transactions amount.
 * In that case - commission will be zero because it have no implemented "minimum fee commission" rule.
 * Only rounding rule was generally applied, but rounding rule is not about min. commission.
 * Rounding rule:
 * Commission fees are rounded up to currency's decimal places. For example, 0.023 EUR should be rounded up to 0.03 EUR
 * @package App\Strategy\FeeCalculation\Withdraw
 */
class PrivateStrategy extends AbstractFeeCalculationStrategy implements CalculationStrategyInterface
{
    /**
     * @var TransactionDataCache
     */
    private TransactionDataCache $cache;

    /**
     * PrivateStrategy constructor.
     * @param WeeklyLimitCalculator $weeklyLimitCalculator
     * @param FeeCommissionConfig $feeCommissionConfig
     */
    public function __construct(
        WeeklyLimitCalculator $weeklyLimitCalculator,
        FeeCommissionConfig $feeCommissionConfig
    ) {
        $this->cache = TransactionDataCache::getInstance();
        parent::__construct($weeklyLimitCalculator, $feeCommissionConfig);
    }

    /**
     * @param Transaction $transaction
     * @return Money
     * @throws \App\Exception\BaseCurrencyNotSpecifiedException
     */
    public function calculate(Transaction $transaction): Money
    {
        $transactionOperationAmount = $transaction->getOperationAmount();
        $feeOperationAmount = clone $transactionOperationAmount;
        $weeklyLimit = $this->weeklyLimitCalculator->calculateWeeklyLimit($transaction);

        // Checking in current transaction operation is free
        if ($this->isTransactionFree($transaction, $weeklyLimit)) {
            $transaction->setFeeAmount(Money::zero($transactionOperationAmount->getCurrency()));
            $this->cache->saveTransaction($transaction);

            return $transaction->getFeeAmount();
        }

        if ($weeklyLimit->getAmount() > 0) {
            $feeOperationAmount->sub($weeklyLimit);
            // Fee amount can not be more than zero
            // For small operations need to reset fee operation amount in case
            if ($feeOperationAmount->getAmount() < 0) {
                $feeOperationAmount->setZero();
            }
        }

        $transaction->setFeeAmount($feeOperationAmount->percent($this->getFeePercent($transaction)));

        $this->cache->saveTransaction($transaction);

        return $transaction->getFeeAmount();
    }

    /**
     * @param Transaction $transaction
     * @param Money $weeklyLimit
     * @return bool
     */
    protected function isTransactionFree(Transaction $transaction, Money $weeklyLimit): bool
    {
        // Weekly amount is less than zero - transaction is not free
        if ($weeklyLimit->getAmount() < 0) {
            return false;
        }

        $weeklyTransactions = $this->cache->getWeeklyTransactions(
            $transaction->getClient()->getClientId(),
            $transaction->getDate()
        );
        // Weekly transactions count more than three - transaction is not free
        if (\count($weeklyTransactions) > $this->weeklyLimitCalculator->getFreeWeeklyOperationsLimit()) {
            return false;
        }

        // Transaction operation amount exceed the weekly limit - transaction is not free
        if ($transaction->getOperationAmount()->gte($weeklyLimit)) {
            return false;
        }

        return true;
    }
}
