<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\FormService;

class FormServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new FormService($this->getConfig($container));
    }

    protected function getConfig(ContainerInterface $container): array
    {
        $config = $container->get('config');
        if (! isset($config['samuelpouzet']['form_resolver'])) {
            throw new \Exception('Form resolver configuration not set');
        }
        return $config['samuelpouzet']['form_resolver'];
    }
}