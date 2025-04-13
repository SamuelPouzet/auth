<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use SamuelPouzet\Auth\Interface\AuthenticationInterface;

class AuthService
{
    public function __construct(
        protected readonly array $config,
        protected readonly IdentityService $identityService,
    ) {
    }

    public function authenticate(MvcEvent $event): bool
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch?->getParam('controller');
        if (is_subclass_of($controller, AuthenticationInterface::class)) {
            //we want to avoid loop redirections
            return true;
        }

        return $this->grantAccess($controller, $routeMatch);
    }

    protected function grantAccess(string $controller, ?RouteMatch $routeMatch): bool
    {
        $permissive = (bool)$this->config['permissive'] ?? false;
        $action = $routeMatch?->getParam('action');

        $configuration = $this->config['access_filter'][$controller][$action] ?? null;

        if (! $configuration && ! $permissive) {
            // no config found and permission is restrictive, no access
            return false;
        }

        if ($configuration === '*') {
            // no check needed
            return true;
        }

        return $this->identityService->hasUser();
    }
}
