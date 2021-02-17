<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Cache\TransactionDataCache;
use App\Configuration\FeeCommissionConfig;
use App\Factory\FeeCalculationStrategyFactory;
use App\Model\Money;
use App\Model\Transaction;
use App\Strategy\CalculationStrategyInterface;

/**
 * Class FeeCalculator implements fee calculation bridge between strategies
 * @package App\Calculator
 */
class FeeCalculator
{
    /**
     * @var FeeCalculationStrategyFactory
     */
    private FeeCalculationStrategyFactory $feeCalculationStrategyFactory;

    /**
     * @var CalculationStrategyInterface
     */
    private CalculationStrategyInterface $strategy;

    /**
     * @var FeeCommissionConfig
     */
    private FeeCommissionConfig $feeCommissionConfig;

    /**
     * @var TransactionDataCache
     */
    private TransactionDataCache $transactionDataCache;

    /**
     * FeeCalculator constructor.
     * @param FeeCalculationStrategyFactory $feeCalculationStrategyFactory
     * @param FeeCommissionConfig $feeCommissionConfig
     */
    public function __construct(
        FeeCalculationStrategyFactory $feeCalculationStrategyFactory,
        FeeCommissionConfig $feeCommissionConfig
    ) {
        $this->feeCalculationStrategyFactory = $feeCalculationStrategyFactory;
        $this->feeCommissionConfig = $feeCommissionConfig;
        $this->transactionDataCache = TransactionDataCache::getInstance();
    }

    /**
     * @param Transaction $transaction
     * @return Money
     * @throws \App\Exception\NotImplementedStrategyException
     */
    public function calculateTransactionFee(Transaction $transaction): Money
    {
        $this->setStrategy($transaction);

        if ($this->isTransactionFree($transaction)) {
            $transaction->setZeroFeeAmount();

            return $transaction->getFeeAmount();
        }

        return $this->strategy->calculate($transaction);
    }

    /**
     * Set strategy for calculation depends on Transaction operation type & client type
     *
     * @param Transaction $transaction
     * @return $this
     * @throws \App\Exception\NotImplementedStrategyException
     */
    protected function setStrategy(Transaction $transaction): self
    {
        $this->strategy = $this->feeCalculationStrategyFactory->getFeeCalculationStrategy(
            $transaction->getOperationType(),
            $transaction->getClient()->getClientType()
        );

        return $this;
    }

    /**
     * Default unchanged rules for every transactions
     *
     * @param Transaction $transaction
     * @return bool
     */
    protected function isTransactionFree(Transaction $transaction): bool
    {
        $clientTransactions = $this->transactionDataCache->getTransactions($transaction->getClient()->getClientId());

        if (\count($clientTransactions) >= $this->feeCommissionConfig->getFreeTransactionsCount()) {
            return false;
        }

        return true;
    }
}
