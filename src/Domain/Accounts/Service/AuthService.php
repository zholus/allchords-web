<?php
declare(strict_types=1);

namespace App\Domain\Accounts\Service;

use App\Domain\Accounts\Permission;
use App\Domain\Accounts\User;
use App\Domain\Accounts\UserUnauthorizedException;
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
        $this->session->set(
            'user',
            new User(
                $userId,
                $username,
                $email,
                $token,
                $refreshToken,
                $expiryAt,
                array_map(
                    fn(string $name) => new Permission($name),
                    $permissions
                )
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
