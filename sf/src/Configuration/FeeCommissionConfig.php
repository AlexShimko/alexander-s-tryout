<?php

declare(strict_types=1);

namespace App\Configuration;

/**
 * Class FeeCommissionConfig
 * @package App\Configuration
 */
class FeeCommissionConfig
{
    private array $fees;

    public function __construct(array $fees)
    {
        $this->fees = $fees;
    }

    public function getFeePercent(string $operationType, string $clientType): float
    {
        if (!isset($this->fees[$operationType][$clientType])) {
            throw new \BadMethodCallException('Fee percent is missed in configuration');
        }

        return (float)$this->fees[$operationType][$clientType];
    }
}
