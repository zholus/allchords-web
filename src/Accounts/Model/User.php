<?php
declare(strict_types=1);

namespace App\Accounts\Model;

use App\Accounts\Permission;

class User
{
    private string $userId;
    private string $username;
    private string $email;

    /**
     * @var Permission[]
     */
    private array $permissions;
    private Token $token;

    public function __construct(
        string $userId,
        string $username,
        string $email,
        array $permissions,
        Token $token
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->permissions = $permissions;
        $this->token = $token;
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

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getToken(): Token
    {
        return $this->token;
    }
}
