<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use SamuelPouzet\Auth\Enumerations\AuthStatusEnum;
use SamuelPouzet\Auth\Interface\AuthenticationInterface;
use SamuelPouzet\Auth\Result\AuthResult;

class AuthService
{
    public function __construct(
        protected array $config,
        protected IdentityService $identityService,
    ) {
    }

    public function authenticate(MvcEvent $event): AuthResult
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch?->getParam('controller');
        if (is_subclass_of($controller, AuthenticationInterface::class)) {
            //we want to avoid loop redirections
            return new AuthResult(AuthStatusEnum::GRANTED, null);
        }

        return $this->grantAccess($controller, $routeMatch);
    }

    protected function grantAccess(string $controller, ?RouteMatch $routeMatch): AuthResult
    {
        $permissive = (bool)$this->config['permissive'] ?? false;
        $action = $routeMatch?->getParam('action');

        $configuration = $this->config['access_filter'][$controller][$action] ?? null;

        if (! $configuration && ! $permissive) {
            // no config found and permission is restrictive, no access
            return new AuthResult(AuthStatusEnum::USER_REQUIRED, null);
        }

        if ($configuration === '*') {
            // no check needed
            return new AuthResult(AuthStatusEnum::GRANTED, null, 'Allowed to everyone');
        }

        if ($this->identityService->hasUser()) {
            return new AuthResult(AuthStatusEnum::GRANTED, null, 'User is connected');
        }
        return new AuthResult(AuthStatusEnum::USER_REQUIRED, null, 'needs connexion');
    }
}
