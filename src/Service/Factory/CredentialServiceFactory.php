<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Interface\Form\AuthFormInterface;
use SamuelPouzet\Auth\Service\CredentialService;
use SamuelPouzet\Auth\Service\FormService;
use SamuelPouzet\Auth\Service\IdentityService;

class CredentialServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CredentialService
    {
        $authenticationService = $container->get(AuthenticationService::class);
        $identityService = $container->get(IdentityService::class);

        return new CredentialService($authenticationService, $identityService);
    }
}
