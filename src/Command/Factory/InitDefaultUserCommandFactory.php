<?php

namespace SamuelPouzet\Auth\Command\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Command\InitDefaultUserCommand;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;
use SamuelPouzet\Auth\Service\UserService;

class InitDefaultUserCommandFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): InitDefaultUserCommand {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        return new InitDefaultUserCommand(
            $entityManager,
            $userService,
            $this->getDefaultUserConfig($container->get('config'))
        );
    }

    protected function getDefaultUserConfig(array $config): array
    {
        $defaultUser = $config['samuelpouzet']['form']['default_user'] ?? null;
        if (! $defaultUser || ! is_array($defaultUser)) {
            throw new ServiceNotCreatedException('No default user configured');
        }
        return $defaultUser;
    }
}
