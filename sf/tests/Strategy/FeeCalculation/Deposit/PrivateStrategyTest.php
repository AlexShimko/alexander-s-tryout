<?php

declare(strict_types=1);

namespace App\Tests\Strategy\FeeCalculation\Deposit;

use App\Enum\ClientTypes;
use App\Enum\OperationTypes;
use App\Model\Client;
use App\Model\Money;
use App\Model\Transaction;
use App\Strategy\FeeCalculation\Deposit\PrivateStrategy;
use App\Tests\Strategy\FeeCalculationStrategyTestCase;

/**
 * Class PrivateStrategyTest
 * @package App\Tests\Strategy\FeeCalculation\Deposit
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
     * @dataProvider transactionProvider
     */
    public function testCalculation(Transaction $transaction, int $expected)
    {
        $this->feeCommissionConfig->method('getFeePercent')->willReturn(0.03);

        $this->assertSame($expected, $this->class->calculate($transaction)->getAmount());
    }

    /**
     * @return array[]
     */
    public function transactionProvider(): array
    {
        $money1 = Money::zero('EUR')->setAmount(20000);
        $money2 = Money::zero('JYP')->setAmount(60000);
        $baseTransaction = new Transaction();
        $baseTransaction->setClient(new Client('1', ClientTypes::PRIVATE));
        $baseTransaction->setOperationType(OperationTypes::DEPOSIT);
        $baseTransaction->setDate(\DateTime::createFromFormat('Y-m-d', '2016-01-06'));

        $transaction0 = (clone $baseTransaction)->setOperationAmount($money1);
        $transaction1 = (clone $baseTransaction)->setOperationAmount($money2);

        return [
            0 => [$transaction0, 6],
            1 => [$transaction1, 18],
        ];
    }
}
