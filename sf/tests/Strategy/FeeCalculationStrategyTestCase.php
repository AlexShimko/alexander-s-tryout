<?php

declare(strict_types=1);

namespace App\Tests\Strategy;

use App\Calculator\WeeklyLimitCalculator;
use App\Configuration\FeeCommissionConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class FeeCalculationStrategyTestCase
 * @package App\Tests\Strategy
 */
abstract class FeeCalculationStrategyTestCase extends TestCase
{
    public $class;

    public WeeklyLimitCalculator $weeklyLimitCalculator;

    public FeeCommissionConfig $feeCommissionConfig;

    public function setUp(): void
    {
        $this->weeklyLimitCalculator = $this->createMock(WeeklyLimitCalculator::class);
        $this->feeCommissionConfig = $this->createMock(FeeCommissionConfig::class);
    }
}
