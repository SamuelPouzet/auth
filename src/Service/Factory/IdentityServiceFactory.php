<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\IdentityService;

class IdentityServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): IdentityService
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authenticationService = $container->get(AuthenticationService::class);

//        $test = $container->get('config')['doctrine']['driver'];
//        die(print_r($test));

        return new IdentityService($entityManager, $authenticationService);
    }
}