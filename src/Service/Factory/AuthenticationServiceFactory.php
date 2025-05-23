<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Adapter\AuthAdapter;

class AuthenticationServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new Session('Purple_auth', 'session', $sessionManager);
        $adapter = $container->get(AuthAdapter::class);
        return new AuthenticationService($sessionManager, $authStorage, $adapter);
    }
}
