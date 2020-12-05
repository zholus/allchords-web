<?php
declare(strict_types=1);

namespace App\Accounts\Service;

use App\Accounts\Model\Token;
use App\Accounts\Model\User;
use App\Accounts\UserUnauthorizedException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthService
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function authenticate(
        string $userId,
        string $username,
        string $email,
        string $token,
        string $refreshToken,
        \DateTimeImmutable $expiryAt,
        array $permissions
    ): void {
        $token = new Token(
            $token,
            $refreshToken,
            $expiryAt
        );

        $this->session->set(
            'user',
            $user = new User(
                $userId,
                $username,
                $email,
                $permissions,
                $token
            )
        );
    }

    public function isAuthenticated(): bool
    {
        return $this->session->get('user') !== null;
    }

    public function getUser(): User
    {
        $user = $this->session->get('user');

        if ($user === null) {
            throw new UserUnauthorizedException('Unauthorized');
        }

        return $user;
    }

    public function logout(): void
    {
        $this->session->remove('user');
    }
}
