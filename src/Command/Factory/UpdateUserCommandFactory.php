<?php

namespace SamuelPouzet\Auth\Command\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Command\CreateUserCommand;
use SamuelPouzet\Auth\Command\UpdateUserCommand;
use SamuelPouzet\Auth\Service\UserService;

class UpdateUserCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UpdateUserCommand
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        return new UpdateUserCommand(
            $entityManager,
            $userService
        );
    }
}
