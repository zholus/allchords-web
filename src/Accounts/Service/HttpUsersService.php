<?php
declare(strict_types=1);

namespace App\Accounts\Service;

use App\Accounts\Model\Token;
use App\Http\HttpClient;
use DateTimeImmutable;

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
        $expireAt = new DateTimeImmutable($responseData['expire_at']);

        $this->authenticate($accessToken, $refreshToken, $expireAt);
    }

    public function signInByRefreshToken(string $refreshToken): void
    {
        $responseData = $this->generateNewToken($refreshToken);

        $accessToken = $responseData['access_token'];
        $refreshToken = $responseData['refresh_token'];
        $expireAt = new DateTimeImmutable($responseData['expire_at']);

        $this->authenticate($accessToken, $refreshToken, $expireAt);
    }

    private function generateNewToken(string $refreshToken): array
    {
        $response = $this->httpClient->post('/api/accounts/auth/refresh-token', [
            'body' => [
                'refresh_token' => $refreshToken,
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

        return json_decode($response->getContent(), true);
    }

    public function updateUserData(Token $token): void
    {
        $this->authenticate(
            $token->getAccessToken(),
            $token->getRefreshToken(),
            $token->getAccessTokenExpiryAt()
        );
    }

    private function authenticate(string $accessToken, string $refreshToken, DateTimeImmutable $expireAt)
    {
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

        $permissions = $this->getUserPermissions($accessToken);

        $this->authService->authenticate(
            $userData['user_id'],
            $userData['username'],
            $userData['email'],
            $accessToken,
            $refreshToken,
            $expireAt,
            $permissions
        );
    }

    private function getUserPermissions(string $accessToken): array
    {
        $response = $this->httpClient->get('/api/accounts/users/authenticated/permissions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);

        return $responseData['permissions'];
    }
}
