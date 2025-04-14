<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Interface\Form\UpdateUserFormInterface;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;
use SamuelPouzet\Auth\Manager\UserManager;
use SamuelPouzet\Auth\Service\UserService;

class UserServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserService
    {
        $config = $container->get('config');
        $userManager = $container->get(UserManager::class);
        return new UserService(
            $this->getUserForm($config),
            $this->getUpdateUserForm($config),
            $userManager
        );
    }

    protected function getUserForm(array $config): UserFormInterface
    {
        $userForm = $config['samuelpouzet']['form']['userForm'] ?? null;
        if (! $userForm) {
            throw new ServiceNotCreatedException('userForm is not configured');
        }
        return new $userForm();
    }

    protected function getUpdateUserForm(array $config): UpdateUserFormInterface
    {
        $updateUserForm = $config['samuelpouzet']['form']['updateUserForm'] ?? null;
        if (! $updateUserForm) {
            throw new ServiceNotCreatedException('updateUserForm is not configured');
        }
        return new $updateUserForm();
    }
}
