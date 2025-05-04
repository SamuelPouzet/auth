<?php

namespace SamuelPouzet\Auth\Command\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Command\CreateUserCommand;
use SamuelPouzet\Auth\Service\UserService;

class CreateUserCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CreateUserCommand
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        return new CreateUserCommand(
            $entityManager,
            $userService
        );
    }
}
