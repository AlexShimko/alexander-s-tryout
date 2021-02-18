<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Client
 * @package App\Model
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass=App\Repository\ClientRepository)
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $clientId;

    /**
     * @ORM\Column(name="client_type", type="string", nullable=false)
     * @var string
     */
    private string $clientType;

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     * @return $this
     */
    public function setClientId(int $clientId): self
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
