<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Model\Money;
use DateTime;

/**
 * Class Transaction
 *
 * @package App\Model
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass=App\Repository\TransactionRepository)
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $id;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="client_id")
     */
    private Client $client;

    /**
     * @var string
     * @ORM\Column(name="operation_type", type="string", nullable=false)
     */
    private string $operationType;

    /**
     * @var Money
     * @ORM\Embedded(class="App\Model\Money", columnPrefix="operation_")
     */
    private Money $operationAmount;

    /**
     * @var Money
     * @ORM\Embedded(class="App\Model\Money", columnPrefix="fee_")
     */
    private Money $feeAmount;

    /**
     * @var DateTime
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private DateTime $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
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
     * @return string
     */
    public function getOperationType(): string
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
     * @return Money
     */
    public function getOperationAmount(): Money
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
