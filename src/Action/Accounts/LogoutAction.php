<?php
declare(strict_types=1);

namespace App\Action\Accounts;

use App\Action\Action;
use App\Domain\Accounts\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LogoutAction extends Action
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request): Response
    {
        $this->authService->logout();

        return $this->redirectToRoute('home_page');
    }
}
