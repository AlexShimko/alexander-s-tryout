<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Calculator\WeeklyLimitCalculator;
use App\Configuration\FeeCommissionConfig;
use App\Enum\ClientTypes;
use App\Enum\OperationTypes;
use App\Exception\NotImplementedStrategyException;
use App\Factory\FeeCalculationStrategyFactory;
use App\Strategy\FeeCalculation\Deposit\BusinessStrategy as DepositBusinessStrategy;
use App\Strategy\FeeCalculation\Deposit\PrivateStrategy as DepositPrivateStrategy;
use App\Strategy\FeeCalculation\Withdraw\BusinessStrategy as WithdrawBusinessStrategy;
use App\Strategy\FeeCalculation\Withdraw\PrivateStrategy as WithdrawPrivateStrategy;
use PHPUnit\Framework\TestCase;

/**
 * Class FeeCalculationStrategyFactoryTest
 * @package App\Tests\Factory
 */
class FeeCalculationStrategyFactoryTest extends TestCase
{
    /**
     * @var FeeCalculationStrategyFactory
     */
    protected FeeCalculationStrategyFactory $class;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->class = new FeeCalculationStrategyFactory(
            $this->createMock(WeeklyLimitCalculator::class),
            $this->createMock(FeeCommissionConfig::class)
        );
    }

    /**
     * @throws NotImplementedStrategyException
     */
    public function testGetCalculationStrategy()
    {
        $this->assertInstanceOf(
            DepositBusinessStrategy::class,
            $this->class->getFeeCalculationStrategy(OperationTypes::DEPOSIT, ClientTypes::BUSINESS)
        );
        $this->assertInstanceOf(
            WithdrawBusinessStrategy::class,
            $this->class->getFeeCalculationStrategy(OperationTypes::WITHDRAW, ClientTypes::BUSINESS)
        );
        $this->assertInstanceOf(
            DepositPrivateStrategy::class,
            $this->class->getFeeCalculationStrategy(OperationTypes::DEPOSIT, ClientTypes::PRIVATE)
        );
        $this->assertInstanceOf(
            WithdrawPrivateStrategy::class,
            $this->class->getFeeCalculationStrategy(OperationTypes::WITHDRAW, ClientTypes::PRIVATE)
        );
    }

    /**
     * @throws NotImplementedStrategyException
     */
    public function testException()
    {
        $this->expectException(NotImplementedStrategyException::class);
        $this->class->getFeeCalculationStrategy(OperationTypes::WITHDRAW, 'newClientType');
    }
}
