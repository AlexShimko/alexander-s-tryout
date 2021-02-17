<?php

declare(strict_types=1);

namespace App\Configuration;

/**
 * Class FeeCommissionConfig
 * @package App\Configuration
 */
class FeeCommissionConfig
{
    /**
     * @var array
     */
    private array $fees;

    /**
     * @var int
     */
    private int $freeTransactionsCount;

    /**
     * FeeCommissionConfig constructor.
     * @param array $fees
     * @param int $freeTransactionsCount
     */
    public function __construct(array $fees, int $freeTransactionsCount)
    {
        $this->fees = $fees;
        $this->freeTransactionsCount = $freeTransactionsCount;
    }

    /**
     * @param string $operationType
     * @param string $clientType
     * @return float
     */
    public function getFeePercent(string $operationType, string $clientType): float
    {
        if (!isset($this->fees[$operationType][$clientType])) {
            throw new \BadMethodCallException('Fee percent is missed in configuration');
        }

        return (float)$this->fees[$operationType][$clientType];
    }

    /**
     * Free transactions count for single client
     *
     * @return int
     */
    public function getFreeTransactionsCount(): int
    {
        return $this->freeTransactionsCount;
    }
}
