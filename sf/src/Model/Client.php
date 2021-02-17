<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Class Client
 * @package App\Model
 */
class Client
{
    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientType;

    /**
     * Client constructor.
     * @param string $clientId
     * @param string $clientType
     */
    public function __construct(string $clientId = '', string $clientType = '')
    {
        $this->setClientId($clientId);
        $this->setClientType($clientType);
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return $this
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientType(): string
    {
        return $this->clientType;
    }

    /**
     * @param string $clientType
     * @return $this
     */
    public function setClientType(string $clientType): self
    {
        $this->clientType = $clientType;

        return $this;
    }
}
