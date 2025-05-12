<?php

namespace SamuelPouzet\Auth\Plugins;

use Laminas\Form\Form;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Mvc\Controller\Plugin\PluginInterface;
use SamuelPouzet\Auth\Service\FormService;

class FormPlugin extends AbstractPlugin implements PluginInterface
{
    public function __construct(protected FormService $formService)
    {
    }

    public function __invoke(string $interface): Form
    {
        return $this->formService->getForm($interface);
    }
}
