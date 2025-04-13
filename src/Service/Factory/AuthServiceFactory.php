<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\AuthService;
use SamuelPouzet\Auth\Service\IdentityService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthService
    {
        $config = $container->get('config')['authentication'] ?? [];
        $identityService = $container->get(IdentityService::class);
        return new AuthService($config, $identityService);
    }
}