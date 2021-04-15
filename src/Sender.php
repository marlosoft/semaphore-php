<?php

namespace Marlosoft\Semaphore;

use DateTime;
use Exception as BaseException;

/**
 * Class Sender
 * @package Marlosoft\Semaphore
 */
class Sender
{
    /** @var string|null $name */
    private $name;

    /** @var string|null $status */
    private $status;

    /** @var DateTime|null $createdAt */
    private $createdAt;

    /**
     * Sender constructor.
     * @param array $data
     * @throws BaseException
     */
    public function __construct($data = [])
    {
        $this->name = $data['name'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->createdAt = new DateTime($data['created']);
    }

    /** @return string|null */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /** @return DateTime|null */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /** @return string */
    public function __toString(): string
    {
        return (string)$this->getName();
    }
}