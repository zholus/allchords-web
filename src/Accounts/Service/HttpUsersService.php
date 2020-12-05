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
        $response = $this->httpClient->post('/api/accounts/auth/sign-in', [
            'body' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);

        if ($response->getStatusCode() >= 300) {
            if ($response->getStatusCode() < 500) {
                $errorMessage = $responseData['message'];
            } else {
                $errorMessage = 'Unexpected error!';
            }

            throw new \DomainException($errorMessage);
        }

        $responseData = json_decode($response->getContent(), true);

        $accessToken = $responseData['access_token'];
        $refreshToken = $responseData['refresh_token'];
        $expireAt = $responseData['expire_at'];

        $response = $this->httpClient->get('/api/accounts/users/authenticated', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);

        if ($response->getStatusCode() >= 300) {
            if ($response->getStatusCode() < 500) {
                $errorMessage = $responseData['message'];
            } else {
                $errorMessage = 'Unexpected error!';
            }

            throw new \DomainException($errorMessage);
        }

        $userData = $responseData['user'];

        $this->authService->authenticate(
            $userData['user_id'],
            $userData['username'],
            $userData['email'],
            $accessToken,
            $refreshToken,
            new \DateTimeImmutable($expireAt),
            []
        );
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
}
