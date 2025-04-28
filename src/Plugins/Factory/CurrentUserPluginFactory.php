<?php

namespace SamuelPouzet\Auth\Plugins\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Plugins\CurrentUserPlugin;
use SamuelPouzet\Auth\Service\IdentityService;

class CurrentUserPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CurrentUserPlugin
    {
        $identityService = $container->get(IdentityService::class);
        return new CurrentUserPlugin($identityService);
    }

}