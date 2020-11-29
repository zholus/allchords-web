<?php
declare(strict_types=1);

namespace App\Accounts\Service;

use App\Http\HttpClient;

final class HttpUsersService implements UsersService
{
    private AuthService $authService;
    private HttpClient $httpClient;

    public function __construct(
        HttpClient $httpClient,
        AuthService $authService
    ) {
        $this->authService = $authService;
        $this->httpClient = $httpClient;
    }

    public function registerUser(string $username, string $email, string $password): void
    {
        $response = $this->httpClient->post('/api/accounts/auth/register', [
            'body' => [
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ],
        ]);

        if ($response->getStatusCode() >= 300) {
            throw new \DomainException('Cannot register');
        }
    }

    public function signInUser(string $email, string $password): void
    {
        $this->usersContract->signInUser($email, $password);

        $token = $this->usersContract->getToken($email);

        $user = $this->usersContract->getUserByToken($token);
        $userPermission = $this->usersContract->getPermissions($user->getUserId());

        $this->authenticate($user, $userPermission);
    }

    public function generateNewToken(string $refreshToken): void
    {
        $this->usersContract->generateNewToken($refreshToken);
    }

    public function getTokenByEmail(string $email): string
    {
        return $this->usersContract->getToken($email);
    }

    public function signInUserByToken(string $token): void
    {
        $user = $this->usersContract->getUserByToken($token);
        $userPermission = $this->usersContract->getPermissions($user->getUserId());

        $this->authenticate($user, $userPermission);
    }

    private function authenticate(UserDto $user, array $userPermissions): void
    {
        $this->authService->authenticate(
            $user->getUserId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getAccessToken(),
            $user->getRefreshToken(),
            $user->getAccessTokenExpiryAt(),
            array_map(
                fn (UserPermissionDto $userPermissionDto) => $userPermissionDto->getName(),
                $userPermissions
            )
        );
    }
}
