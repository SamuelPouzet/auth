<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\Form\Form;
use Exception;

class FormService
{
    public function __construct(protected readonly array $config)
    {
    }

    protected function resolve(string $interface): string
    {
        $className = $this->config[$interface];
        if (! isset($className)) {
            throw new Exception(sprintf('Interface "%s" isn\'t bound to a form.', $interface));
        }
        if (! class_exists($className)) {
            throw new Exception(sprintf('Class "%s" doesn\'t exist.', $className));
        }

        if (! is_subclass_of($className, $interface)) {
            throw new Exception(sprintf('Class "%s" doesn\'t implement %s.', $className, $interface));
        }

        return $className;
    }

    public function getForm(string $interface): Form
    {
        $className = $this->resolve($interface);
        return new $className();
    }
}
