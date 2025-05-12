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
use SamuelPouzet\Auth\Service\MailerService;
use SamuelPouzet\Auth\Service\UserService;

class UserServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserService
    {
        $config = $container->get('config');
        $userManager = $container->get(UserManager::class);
        $mailerService = $container->get(MailerService::class);
        $renderer = $container->get(PhpRenderer::class);
        return new UserService(
            $this->getUserForm($config),
            $this->getUpdateUserForm($config),
            $this->getTokenForm($config),
            $this->getReinitPasswordForm($config),
            $this->getReloadPasswordForm($config),
            $userManager,
            $mailerService,
            $renderer
        );
    }

    protected function getUserForm(array $config): UserFormInterface
    {
        $userForm = $config['samuelpouzet']['form']['userForm'] ?? null;
        if (! $userForm) {
            throw new ServiceNotCreatedException('userForm is not configured');
        }
        return new $userForm();
    }

    protected function getUpdateUserForm(array $config): UpdateUserFormInterface
    {
        $updateUserForm = $config['samuelpouzet']['form']['updateUserForm'] ?? null;
        if (! $updateUserForm) {
            throw new ServiceNotCreatedException('updateUserForm is not configured');
        }
        return new $updateUserForm();
    }

    protected function getTokenForm(array $config): TokenFormInterface
    {
        $tokenForm = $config['samuelpouzet']['form']['tokenForm'] ?? null;
        if (! $tokenForm) {
            throw new ServiceNotCreatedException('tokenForm is not configured');
        }
        return new $tokenForm();
    }

    protected function getReinitPasswordForm(array $config): ReinitPasswordFormInterface
    {
        $tokenForm = $config['samuelpouzet']['form']['reinitPasswordForm'] ?? null;
        if (! $tokenForm) {
            throw new ServiceNotCreatedException('reinitPasswordForm is not configured');
        }
        return new $tokenForm();
    }

    protected function getReloadPasswordForm(array $config): ReloadPasswordFormInterface
    {
        $tokenForm = $config['samuelpouzet']['form']['reloadPasswordForm'] ?? null;
        if (! $tokenForm) {
            throw new ServiceNotCreatedException('reloadPasswordForm is not configured');
        }
        return new $tokenForm();
    }
}
