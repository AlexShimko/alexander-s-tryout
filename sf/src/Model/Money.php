<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Money implements money model with required math operations implemented.
 * Instead of "float" amount we want to store amount in "int".
 * Reason - pure mathematical calculation.
 * File reader or any analog must mul by 100 incoming float value to have an option to calculate amount with penny.
 * E.g.:
 * (float) 1.00 = (int) 100
 * (float) 0.06 = (int) 6
 * For proper money displaying see: App\Helper\MoneyView
 * @package App\Model
 *
 * @ORM\Embeddable
*/
class Money
{
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $amount;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $currency;

    /**
     * Money constructor.
     * @param int $amount
     * @param string $currency
     */
    public function __construct(int $amount = 0, string $currency = '')
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return $this
     */
    public function setZero(): self
    {
        $this->setAmount(0);

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Create Money instance with zero amount
     * Require $currency to be provided
     *
     * @param string $currency
     * @return Money
     */
    public static function zero(string $currency): Money
    {
        return new Money(0, $currency);
    }

    /**
     * Alias for "Greater or equal"
     *
     * @param Money $money
     * @return bool
     */
    public function gte(Money $money): bool
    {
        return $this->getAmount() >= $money->getAmount();
    }

    /**
     * Alias for "Greater"
     *
     * @param Money $money
     * @return bool
     */
    public function gt(Money $money): bool
    {
        return $this->getAmount() > $money->getAmount();
    }

    /**
     * Alias for "addition" operation
     * Using current instance for operation
     *
     * @param Money $money
     * @return $this
     */
    public function add(Money $money): Money
    {
        return $this->setAmount($this->getAmount() + $money->getAmount());
    }

    /**
     * Alias for "subtraction" operation
     * Using current instance for operation
     *
     * @param Money $money
     * @return $this
     */
    public function sub(Money $money): Money
    {
        return $this->setAmount($this->getAmount() - $money->getAmount());
    }

    /**
     * Calculating percent of current instance value and return new instance
     *
     * @param float $percent
     * @return $this
     */
    public function percent(float $percent): Money
    {
        $percentAmount = clone $this;
        $percentValue = $percent / 100;
        $value = (int)\round($this->getAmount() * $percentValue, 2);

        return $percentAmount->setAmount($value);
    }

    /**
     * Converting instance amount by exchange rate and return new instance
     *
     * @param string $toCurrency
     * @param $rate
     * @return Money
     */
    public function convertByRate(string $toCurrency, $rate): Money
    {
        $newMoney = clone $this;
        $newMoney->setAmount((int)($this->getAmount() / $rate))
            ->setCurrency($toCurrency);

        return $newMoney;
    }
}
