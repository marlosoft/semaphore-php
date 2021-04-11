<?php

namespace Marlosoft\Semaphore;

use DateTime;
use Exception as BaseException;

/**
 * Class Message
 * @package Marlosoft\Semaphore
 */
class Message
{
    /** @var int $id */
    private $id;

    /** @var int $userId */
    private $userId;

    /** @var string $user */
    private $user;

    /** @var int $accountId */
    private $accountId;

    /** @var string $account */
    private $account;

    /** @var string $recipient */
    private $recipient;

    /** @var string $message */
    private $message;

    /** @var string|null $code */
    private $code = null;

    /** @var string $senderName */
    private $senderName;

    /** @var string $network */
    private $network;

    /** @var string $status */
    private $status;

    /** @var string $type */
    private $type;

    /** @var string $source */
    private $source;

    /** @var DateTime $createdAt */
    private $createdAt;

    /** @var DateTime $updatedAt */
    private $updatedAt;

    /**
     * Message constructor.
     * @param array $data
     * @throws BaseException
     */
    public function __construct($data = [])
    {
        $this->id = $data['message_id'];
        $this->userId = $data['user_id'];
        $this->user = $data['user'];
        $this->accountId = $data['account_id'];
        $this->account = $data['account'];
        $this->recipient = $data['recipient'];
        $this->message = $data['message'];
        $this->code = $data['code'] ?? null;
        $this->senderName = $data['sender_name'];
        $this->network = $data['network'];
        $this->status = $data['status'];
        $this->type = $data['type'];
        $this->source = $data['source'];
        $this->createdAt = new DateTime($data['created_at']);
        $this->updatedAt = new DateTime($data['updated_at']);
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
    }

    /** @return int */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /** @return string */
    public function getUser(): string
    {
        return $this->user;
    }

    /** @return int */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /** @return string */
    public function getAccount(): string
    {
        return $this->account;
    }

    /** @return string */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /** @return string */
    public function getMessage(): string
    {
        return $this->message;
    }

    /** @return string|null */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /** @return string */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /** @return string */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    /** @return string */
    public function getType(): string
    {
        return $this->type;
    }

    /** @return string */
    public function getSource(): string
    {
        return $this->source;
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