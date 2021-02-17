<?php

declare(strict_types=1);

namespace App\Factory;

use App\Calculator\WeeklyLimitCalculator;
use App\Configuration\FeeCommissionConfig;
use App\Exception\NotImplementedStrategyException;
use App\Strategy\CalculationStrategyInterface;

/**
 * Class FeeCalculationStrategyFactory implements factory which will produce strategy class based on:
 * $operationType;
 * $clientType;
 * Require $baseNamespace to exist and contains strategy class with pattern:
 * App\Strategy\FeeCalculation\{$operationType}\{$clientType}Strategy
 * E.g.:
 * App\Strategy\FeeCalculation\Deposit\BusinessStrategy
 * App\Strategy\FeeCalculation\AnythingYou\WantStrategy
 *
 * @package App\Factory
 */
class FeeCalculationStrategyFactory
{
    /**
     * @var string contains first (most likely unchanged) part of namespace
     */
    private string $baseNamespace = "App\Strategy\FeeCalculation\\";

    /**
     * @var WeeklyLimitCalculator
     */
    private WeeklyLimitCalculator $weeklyLimitCalculator;

    /**
     * @var FeeCommissionConfig
     */
    private FeeCommissionConfig $feeCommissionConfig;

    /**
     * FeeCalculationStrategyFactory constructor.
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
     * @param string $operationType
     * @param string $clientType
     * @return CalculationStrategyInterface
     * @throws NotImplementedStrategyException
     */
    public function getFeeCalculationStrategy(
        string $operationType,
        string $clientType
    ): CalculationStrategyInterface {
        $strategyClassName = \sprintf(
            '%s%s\\%sStrategy',
            $this->baseNamespace,
            \ucfirst($operationType),
            \ucfirst($clientType)
        );

        if (!\class_exists($strategyClassName)) {
            throw new NotImplementedStrategyException();
        }

        return new $strategyClassName($this->weeklyLimitCalculator, $this->feeCommissionConfig);
    }
}
