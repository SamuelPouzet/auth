<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Form\AuthForm;
use SamuelPouzet\Auth\Service\CredentialService;
use SamuelPouzet\Auth\Service\IdentityService;

class CredentialServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CredentialService
    {
        $form = new AuthForm();
        $authenticationService = $container->get(AuthenticationService::class);
        $identityService = $container->get(IdentityService::class);
        return new CredentialService($form, $authenticationService, $identityService);
    }
}
