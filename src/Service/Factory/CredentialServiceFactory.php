<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Form\Interface\AuthFormInterface;
use SamuelPouzet\Auth\Service\CredentialService;
use SamuelPouzet\Auth\Service\IdentityService;

class CredentialServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CredentialService
    {
        $config = $container->get('config');
        $authenticationService = $container->get(AuthenticationService::class);
        $identityService = $container->get(IdentityService::class);
        return new CredentialService($this->getAuthForm($config), $authenticationService, $identityService);
    }

    protected function getAuthForm(array $config): AuthFormInterface
    {
        $authForm = $config['samuelpouzet']['form']['authForm'] ?? null;
        if (! $authForm) {
            throw new ServiceNotCreatedException('authForm is not configured');
        }
        return new $authForm();
    }
}
