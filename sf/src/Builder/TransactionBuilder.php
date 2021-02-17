<?php

declare(strict_types=1);

namespace App\Builder;

use App\Exception\InvalidInputDataFormatException;
use App\Model\Client;
use App\Model\Money;
use App\Model\Transaction;

/**
 * Class TransactionBuilder implements builder for Transaction model
 * Current version contains:
 * Builder from csv file with strict data format
 * @package App\Builder
 */
class TransactionBuilder
{
    /**
     * Using strict data format here:
     *   0 => string '2014-12-31'
     *   1 => string '4'
     *   2 => string 'private'
     *   3 => string 'withdraw'
     *   4 => string '1200.00'
     *   5 => string 'EUR'
     *
     * @param array $csvLineData
     * @return Transaction
     *
     * @throws InvalidInputDataFormatException
     */
    public function buildTransaction(array $csvLineData): Transaction
    {
        $transaction = new Transaction();
        try {
            $transaction->setDate(\DateTime::createFromFormat('Y-m-d', $csvLineData[0]));
            $transaction->setClient(new Client(
                $csvLineData[1],
                $csvLineData[2]
            ));
            $transaction->setOperationType($csvLineData[3]);
            $transaction->setOperationAmount(new Money((int)($csvLineData[4] * 100), $csvLineData[5]));
        } catch (\Throwable $exception) {
            throw new InvalidInputDataFormatException();
        }

        return $transaction;
    }
}
