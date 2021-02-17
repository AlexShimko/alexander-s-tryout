<?php

declare(strict_types=1);

namespace App\Tests\Strategy\FeeCalculation\Withdraw;

use App\Enum\ClientTypes;
use App\Enum\OperationTypes;
use App\Model\Client;
use App\Model\Money;
use App\Model\Transaction;
use App\Strategy\FeeCalculation\Withdraw\PrivateStrategy;
use App\Tests\Strategy\FeeCalculationStrategyTestCase;

/**
 * Class PrivateStrategyTest
 * @package App\Tests\Strategy\FeeCalculation\Withdraw
 */
class PrivateStrategyTest extends FeeCalculationStrategyTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->class = new PrivateStrategy(
            $this->weeklyLimitCalculator,
            $this->feeCommissionConfig
        );
    }

    /**
     * @param Transaction $transaction
     * @param int $expected
     * @dataProvider transactionWithLimitProvider
     */
    public function testCalculation(Transaction $transaction, int $expected)
    {
        $this->class->getWeeklyLimitCalculator()
            ->method('calculateWeeklyLimit')
            ->willReturn(new Money(100000, 'EUR'));

        $this->feeCommissionConfig->method('getFeePercent')->willReturn(0.3);

        $this->assertSame($expected, $this->class->calculate($transaction)->getAmount());
    }

    /**
     * @param Transaction $transaction
     * @param int $expected
     * @dataProvider transactionWithZeroLimitProvider
     */
    public function testCalculationWithZeroLimit(Transaction $transaction, int $expected)
    {
        $this->class->getWeeklyLimitCalculator()
            ->method('calculateWeeklyLimit')
            ->willReturn(new Money(0, 'EUR'));

        $this->feeCommissionConfig->method('getFeePercent')->willReturn(0.3);

        $this->assertSame($expected, $this->class->calculate($transaction)->getAmount());
    }

    /**
     * @return array[]
     */
    public function transactionWithLimitProvider(): array
    {
        $money1 = Money::zero('EUR')->setAmount(20000);
        $money2 = Money::zero('JYP')->setAmount(60000);
        $money3 = Money::zero('EUR')->setAmount(120000);
        $baseTransaction = new Transaction();
        $baseTransaction->setClient(new Client('1', ClientTypes::BUSINESS));
        $baseTransaction->setOperationType(OperationTypes::WITHDRAW);
        $baseTransaction->setDate(\DateTime::createFromFormat('Y-m-d', '2016-01-06'));

        $transaction0 = (clone $baseTransaction)->setOperationAmount($money1);
        $transaction1 = (clone $baseTransaction)->setOperationAmount($money2);
        $transaction2 = (clone $baseTransaction)->setOperationAmount($money3);

        return [
            0 => [$transaction0, 0],
            1 => [$transaction1, 0],
            2 => [$transaction2, 60],
        ];
    }

    /**
     * @return array[]
     */
    public function transactionWithZeroLimitProvider(): array
    {
        $money1 = Money::zero('USD')->setAmount(10000);
        $money2 = Money::zero('JYP')->setAmount(60000);
        $money3 = Money::zero('EUR')->setAmount(120000);
        $baseTransaction = new Transaction();
        $baseTransaction->setClient(new Client('1', ClientTypes::BUSINESS));
        $baseTransaction->setOperationType(OperationTypes::WITHDRAW);
        $baseTransaction->setDate(\DateTime::createFromFormat('Y-m-d', '2016-01-06'));

        $transaction0 = (clone $baseTransaction)->setOperationAmount($money1);
        $transaction1 = (clone $baseTransaction)->setOperationAmount($money2);
        $transaction2 = (clone $baseTransaction)->setOperationAmount($money3);

        return [
            0 => [$transaction0, 30],
            1 => [$transaction1, 180],
            2 => [$transaction2, 360],
        ];
    }
}
