<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Form\UserForm;
use SamuelPouzet\Auth\Manager\UserManager;
use SamuelPouzet\Auth\Service\UserService;

class UserServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserService
    {
        $form = new UserForm();
        $userManager = $container->get(UserManager::class);
        return new UserService($form, $userManager);
    }
}
