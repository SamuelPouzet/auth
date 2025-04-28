<?php

namespace SamuelPouzet\Auth\View\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Auth\View\CurrentUserHelper;

class CurrentUserHelperFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CurrentUserHelper
    {
        $identityService = $container->get(IdentityService::class);
        return new CurrentUserHelper($identityService);
    }
}