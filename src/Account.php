<?php


namespace Marlosoft\Semaphore;

/**
 * Class Account
 * @package Marlosoft\Semaphore
 */
class Account
{
    /** @var int|null $id */
    private $id;

    /** @var string|null $name */
    private $name;

    /** @var string|null $status */
    private $status;

    /** @var float|null $balance  */
    private $balance;

    /**
     * Account constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->id = $data['account_id'] ?? null;
        $this->name = $data['account_name'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->balance = $data['credit_balance'] ?? 0;
    }

    /** @return int|null */
    public function getId(): ?int
    {
        return $this->id;
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

    /** @return float|null */
    public function getBalance(): ?float
    {
        return $this->balance;
    }
}