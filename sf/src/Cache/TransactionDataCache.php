<?php

declare(strict_types=1);

namespace App\Cache;

use App\Model\Transaction;

/**
 * Class TransactionDataCache implement script-based cache "Transaction"s store
 * @package App\Cache
 */
class TransactionDataCache extends AbstractDataCache
{
    /**
     * Save Transaction in cache by generating the key
     *
     * @param Transaction $transaction
     */
    public function saveTransaction(Transaction $transaction): void
    {
        $this->setCache(
            $this->generateTransactionKey(
                $transaction->getClient()->getClientId(),
                $transaction->getDate()
            ),
            $transaction
        );
    }

    /**
     * Get weekly transactions from cache
     *
     * @param string $clientId
     * @param \DateTimeInterface $transactionDate
     * @return array
     */
    public function getWeeklyTransactions(string $clientId, \DateTimeInterface $transactionDate): array
    {
        return $this->getCache($this->generateTransactionKey($clientId, $transactionDate));
    }

    /**
     * Generate cache key based on generated date key and client id
     * e.g.:
     * clientId = 4;
     * transactionDate = 2014-12-31
     * output = 4-201501
     *
     * @param string $clientId
     * @param \DateTimeInterface $transactionDate
     * @return string
     */
    protected function generateTransactionKey(string $clientId, \DateTimeInterface $transactionDate): string
    {
        return $clientId . '-' . $transactionDate->format('oW');
    }
}
