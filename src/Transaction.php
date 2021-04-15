<?php

namespace Marlosoft\Semaphore;

use DateTime;
use Exception as BaseException;

/**
 * Class Transaction
 * @package Marlosoft\Semaphore
 */
class Transaction
{
    /** @var int|null $id */
    private $id;

    /** @var int|null $userId */
    private $userId;

    /** @var string|null $user */
    private $user;

    /** @var string|null $status */
    private $status;

    /** @var string|null $method */
    private $method;

    /** @var string|null $externalId */
    private $externalId;

    /** @var string|null $amount */
    private $amount;

    /** @var string|null $credit */
    private $credit;

    /** @var DateTime $createdAt */
    private $createdAt;

    /** @var DateTime $updatedAt */
    private $updatedAt;

    /**
     * Transaction constructor.
     * @param array $data
     * @throws BaseException
     */
    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->user = $data['user'] ?? null;
        $this->userId = $data['user_id'] ?? null;
        $this->status = $data['status'] ?? null;;
        $this->method = $data['transaction_method'] ?? null;;
        $this->externalId = $data['external_transaction_id'] ?? null;;
        $this->amount = $data['amount'] ?? null;;
        $this->credit = $data['credit_value'] ?? null;
        $this->createdAt = new DateTime($data['created_at']);
        $this->updatedAt = new DateTime($data['updated_at']);
    }

    /** @return int|null */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return int|null */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /** @return string|null */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /** @return string|null */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /** @return string|null */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /** @return string|null */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /** @return string|null */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /** @return string|null */
    public function getCredit(): ?string
    {
        return $this->credit;
    }

    /** @return DateTime */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /** @return DateTime */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}