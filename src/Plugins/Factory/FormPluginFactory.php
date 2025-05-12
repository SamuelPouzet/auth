<?php

namespace SamuelPouzet\Auth\Plugins\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Plugins\FormPlugin;
use SamuelPouzet\Auth\Service\FormService;

class FormPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): FormPlugin
    {
        $formService = $container->get(FormService::class);
        return new FormPlugin($formService);
    }
}
