<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

/**
 * Class Transaction
 * @package App\Model
 */
class Transaction
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private string $operationType;

    /**
     * @var Money
     */
    private Money $operationAmount;

    /**
     * @var Money
     */
    private Money $feeAmount;

    /**
     * @var DateTime
     */
    private DateTime $date;

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    /**
     * @param string $operationType
     * @return $this
     */
    public function setOperationType(string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getOperationAmount(): ?Money
    {
        return $this->operationAmount;
    }

    /**
     * Alias for get currency in single call
     *
     * @return string
     */
    public function getOperationCurrency(): string
    {
        return $this->getOperationAmount()->getCurrency();
    }

    /**
     * @param Money $operationAmount
     * @return $this
     */
    public function setOperationAmount(Money $operationAmount): self
    {
        $this->operationAmount = $operationAmount;

        return $this;
    }

    /**
     * @return Money
     */
    public function getFeeAmount(): Money
    {
        return $this->feeAmount;
    }

    /**
     * @param Money $feeAmount
     * @return $this
     */
    public function setFeeAmount(Money $feeAmount): Transaction
    {
        $this->feeAmount = $feeAmount;

        return $this;
    }

    /**
     * Set zero fee amount
     *
     * @return $this
     */
    public function setZeroFeeAmount(): Transaction
    {
        $this->feeAmount = Money::zero($this->getOperationCurrency());

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
