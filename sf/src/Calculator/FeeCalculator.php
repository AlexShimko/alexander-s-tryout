<?php

declare(strict_types=1);

namespace App\Calculator;

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
     * FeeCalculator constructor.
     * @param FeeCalculationStrategyFactory $feeCalculationStrategyFactory
     */
    public function __construct(FeeCalculationStrategyFactory $feeCalculationStrategyFactory)
    {
        $this->feeCalculationStrategyFactory = $feeCalculationStrategyFactory;
    }

    /**
     * @param Transaction $transaction
     * @return Money
     * @throws \App\Exception\NotImplementedStrategyException
     */
    public function calculateTransactionFee(Transaction $transaction): Money
    {
        $this->setStrategy($transaction);

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
}
