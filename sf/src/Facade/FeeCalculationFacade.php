<?php

declare(strict_types=1);

namespace App\Facade;

use App\Calculator\FeeCalculator;
use App\Exception\NotImplementedStrategyException;
use App\Factory\TransactionFactory;
use App\Helper\MoneyView;
use App\Model\Transaction;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FeeCalculationFacade
 * @package App\Facade
 */
class FeeCalculationFacade
{
    /**
     * @var TransactionFactory
     */
    private TransactionFactory $transactionFactory;

    /**
     * @var FeeCalculator
     */
    private FeeCalculator $feeCalculator;

    /**
     * @var MoneyView
     */
    private MoneyView $moneyView;

    /**
     * FeeCalculationFacade constructor.
     * @param TransactionFactory $transactionFactory
     * @param FeeCalculator $feeCalculator
     * @param MoneyView $moneyView
     */
    public function __construct(
        TransactionFactory $transactionFactory,
        FeeCalculator $feeCalculator,
        MoneyView $moneyView
    ) {
        $this->transactionFactory = $transactionFactory;
        $this->feeCalculator = $feeCalculator;
        $this->moneyView = $moneyView;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return array
     */
    public function calculateFeesFromFile(UploadedFile $uploadedFile): array
    {
        $transactions = $this->transactionFactory->getTransactionsFromFile($uploadedFile);
        $moneyView = $this->moneyView;
        $output = [];
        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            try {
                $output[] = $moneyView($this->feeCalculator->calculateTransactionFee($transaction));
            } catch (NotImplementedStrategyException $e) {
                // Logger or exception there
            }
        }

        return $output;
    }
}
