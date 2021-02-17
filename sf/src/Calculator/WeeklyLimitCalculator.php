<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Cache\TransactionDataCache;
use App\Enum\CurrencyEnum;
use App\Model\Money;
use App\Model\Transaction;
use App\Service\ExchangeRateService;

/**
 * Class WeeklyLimitCalculator implement calculation for customer weekly limit
 * @package App\Calculator
 */
class WeeklyLimitCalculator
{
    /**
     * @var ExchangeRateService
     */
    private ExchangeRateService $exchangeRateService;

    /**
     * @var int
     */
    private int $freeWeeklyOperationsLimit;

    /**
     * @var int
     */
    private int $weeklyLimitEur;

    /**
     * @var TransactionDataCache
     */
    private TransactionDataCache $cache;

    /**
     * WeeklyLimitCalculator constructor.
     * @param ExchangeRateService $exchangeRateService
     * @param int $freeWeeklyOperationsLimit
     * @param int $weeklyLimitEur
     */
    public function __construct(
        ExchangeRateService $exchangeRateService,
        int $freeWeeklyOperationsLimit,
        int $weeklyLimitEur
    ) {
        $this->exchangeRateService = $exchangeRateService;
        $this->freeWeeklyOperationsLimit = $freeWeeklyOperationsLimit;
        $this->weeklyLimitEur = $weeklyLimitEur;
        $this->cache = TransactionDataCache::getInstance();
    }

    /**
     * Weekly limit calculation based on Transaction's date
     * Any Transaction that was done already by customer will be taken from cache depends on:
     * week and year of current Transaction
     *
     * @param Transaction $transaction
     * @return Money
     * @throws \App\Exception\BaseCurrencyNotSpecifiedException
     */
    public function calculateWeeklyLimit(Transaction $transaction): Money
    {
        $weeklyLimit = new Money($this->weeklyLimitEur, CurrencyEnum::getDefaultCurrency());

        // Transaction currency that we will use weekly limit calculation to have consistent money values
        $transactionCurrency = $transaction->getOperationAmount()->getCurrency();

        // If our weekly limit is in another currency - convert it
        if ($transactionCurrency !== CurrencyEnum::getDefaultCurrency()) {
            $rate = $this->getExchangeRates($transaction)[CurrencyEnum::getDefaultCurrency()];
            $weeklyLimit = $weeklyLimit->convertByRate($transactionCurrency, $rate);
        }

        // Getting client transactions from in-memory cache
        if ($clientTransactions = $this->cache->getWeeklyTransactions(
            $transaction->getClient()->getClientId(),
            $transaction->getDate()
        )) {
            /** @var Transaction $transaction */
            foreach ($clientTransactions as $clientTransaction) {
                $operationAmount = $clientTransaction->getOperationAmount();
                // If operation amount currency differs from current transaction - convert it
                if ($operationAmount->getCurrency() !== $transactionCurrency) {
                    $rate = $this->getExchangeRates($transaction)[$operationAmount->getCurrency()];
                    $operationAmount = $operationAmount->convertByRate($transactionCurrency, $rate);
                }

                $weeklyLimit->sub($operationAmount);

                // Have no reason to continue calculate weekly limit if it less than zero
                // Set value to 0 and return result
                if ($weeklyLimit->getAmount() < 0) {
                    $weeklyLimit->setZero();
                    break;
                }
            }
        }

        return $weeklyLimit;
    }

    /**
     * @return int
     */
    public function getFreeWeeklyOperationsLimit(): int
    {
        return $this->freeWeeklyOperationsLimit;
    }

    /**
     * @param Transaction $transaction
     * @return array
     * @throws \App\Exception\BaseCurrencyNotSpecifiedException
     */
    protected function getExchangeRates(Transaction $transaction): array
    {
        return $this->exchangeRateService->getExchangeRateForCurrency(
            $transaction->getOperationAmount()->getCurrency(),
            $transaction->getDate()
        );
    }
}
