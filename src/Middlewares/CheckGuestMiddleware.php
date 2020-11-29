<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Domain\Accounts\Service\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Zholus\SymfonyMiddleware\MiddlewareInterface;

final class CheckGuestMiddleware implements MiddlewareInterface
{
    private AuthService $authService;
    private RouterInterface $router;

    public function __construct(AuthService $authService, RouterInterface $router)
    {
        $this->authService = $authService;
        $this->router = $router;
    }

    public function handle(Request $request): ?Response
    {
        if ($this->authService->isAuthenticated()) {
            return new RedirectResponse(
                $this->router->generate('home_page')
            );
        }

        return null;
    }
}