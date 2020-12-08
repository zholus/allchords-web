<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Accounts\PermissionsMap;
use App\Accounts\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zholus\SymfonyMiddleware\MiddlewareInterface;

final class PermissionMiddleware implements MiddlewareInterface
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request): ?Response
    {
        $controllerFqcn = $request->attributes->get('_controller');

        $requiredPermissionsForAction = PermissionsMap::MAP[$controllerFqcn] ?? null;

        if ($requiredPermissionsForAction === null) {
            return null;
        }

        $userPermission = $this->authService->getUser()->getPermissions();

        foreach ($userPermission as $permission) {
            if (in_array($permission->getName(), $requiredPermissionsForAction, true)) {
                return null;
            }
        }

        return new Response('Access denied.', Response::HTTP_FORBIDDEN);
    }
}
