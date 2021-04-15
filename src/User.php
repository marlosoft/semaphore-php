<?php

namespace Marlosoft\Semaphore;

/**
 * Class User
 * @package Marlosoft\Semaphore
 */
class User
{
    /** @var int|null $id */
    private $id;

    /** @var string|null $email */
    private $email;

    /** @var string|null $role */
    private $role;

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->id = $data['user_id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->role = $data['role'] ?? null;
    }

    /** @return int|null */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return string|null */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /** @return string|null */
    public function getRole(): ?string
    {
        return $this->role;
    }
}