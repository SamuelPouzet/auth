<?php

namespace SamuelPouzet\Auth\Command\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Command\UpdatePasswordCommand;
use SamuelPouzet\Auth\Service\UserService;

class UpdatePasswordCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UpdatePasswordCommand
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        return new UpdatePasswordCommand($entityManager, $userService);
    }
}
