<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Interface\Form\ReinitPasswordFormInterface;
use SamuelPouzet\Auth\Interface\Form\ReloadPasswordFormInterface;
use SamuelPouzet\Auth\Interface\Form\TokenFormInterface;
use SamuelPouzet\Auth\Interface\Form\UpdateUserFormInterface;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;
use SamuelPouzet\Auth\Manager\UserManager;
use SamuelPouzet\Auth\Service\EmailService;
use SamuelPouzet\Auth\Service\FormService;
use SamuelPouzet\Auth\Service\MailerService;
use SamuelPouzet\Auth\Service\UserService;

class UserServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserService
    {
        $userManager = $container->get(UserManager::class);
        $emailService = $container->get(EmailService::class);
        $formService = $container->get(FormService::class);
        return new UserService(
            $emailService,
            $userManager,
            $formService,
        );
    }
}
