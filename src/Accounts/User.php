<?php
declare(strict_types=1);

namespace App\Accounts;

use DateTimeImmutable;

class User
{
    private string $userId;
    private string $username;
    private string $email;
    private string $token;
    private string $refreshToken;
    private DateTimeImmutable $expiryAt;

    /**
     * @var Permission[]
     */
    private array $permissions;

    public function __construct(
        string $userId,
        string $username,
        string $email,
        string $token,
        string $refreshToken,
        DateTimeImmutable $expiryAt,
        array $permissions
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->token = $token;
        $this->refreshToken = $refreshToken;
        $this->expiryAt = $expiryAt;
        $this->permissions = $permissions;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiryAt(): DateTimeImmutable
    {
        return $this->expiryAt;
    }
}