<?php
declare(strict_types=1);

namespace App\Accounts\Model;

class Token
{
    private string $accessToken;
    private string $refreshToken;
    private \DateTimeImmutable $accessTokenExpiryAt;

    public function __construct(
        string $accessToken,
        string $refreshToken,
        \DateTimeImmutable $accessTokenExpiryAt
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->accessTokenExpiryAt = $accessTokenExpiryAt;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getAccessTokenExpiryAt(): \DateTimeImmutable
    {
        return $this->accessTokenExpiryAt;
    }
}
