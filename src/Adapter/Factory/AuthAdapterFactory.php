<?php

namespace SamuelPouzet\Auth\Adapter\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Adapter\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthAdapter
    {
        return new AuthAdapter($container->get('doctrine.entitymanager.orm_default'));
    }
}