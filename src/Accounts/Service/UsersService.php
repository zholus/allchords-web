<?php
declare(strict_types=1);

namespace App\Accounts\Service;

use App\Accounts\Model\Token;

interface UsersService
{
    public function registerUser(string $username, string $email, string $password): void;
    public function signInUser(string $email, string $password): void;
    public function updateUserData(Token $token): void;
    public function signInByRefreshToken(string $refreshToken): void;
}
