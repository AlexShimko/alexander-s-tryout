<?php

declare(strict_types=1);

namespace App\Factory;

use App\Builder\TransactionBuilder;
use App\Exception\InvalidInputDataFormatException;
use App\Helper\CsvReader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class TransactionFactory
 * @package App\Factory
 */
class TransactionFactory
{
    /**
     * @var TransactionBuilder
     */
    private TransactionBuilder $transactionBuilder;

    /**
     * TransactionFactory constructor.
     * @param TransactionBuilder $transactionBuilder
     */
    public function __construct(TransactionBuilder $transactionBuilder)
    {
        $this->transactionBuilder = $transactionBuilder;
    }

    /**
     * Build Transactions using TransactionBuilder and return them as array of Transaction's
     *
     * @param UploadedFile $uploadedFile
     * @return array
     */
    public function getTransactionsFromFile(UploadedFile $uploadedFile): array
    {
        $transactions = [];
        $csvReader = $this->getCsvReader($uploadedFile);
        foreach ($csvReader as $lineData) {
            try {
                $transactions[] = $this->transactionBuilder->buildTransaction($lineData);
            } catch (InvalidInputDataFormatException $exception) {
                // There could be logger or exception throw
                // Prefer to just skip invalid lines in terms of custom inputs testing
                continue;
            }
        }

        return $transactions;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return CsvReader
     */
    protected function getCsvReader(UploadedFile $uploadedFile): CsvReader
    {
        return new CsvReader($uploadedFile);
    }
}
