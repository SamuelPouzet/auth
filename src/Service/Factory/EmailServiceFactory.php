<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\EmailService;
use SamuelPouzet\Auth\Service\MailerService;

class EmailServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): EmailService
    {
        $renderer = $container->get(PhpRenderer::class);
        $mailerService = $container->get(MailerService::class);
        return new EmailService($renderer, $mailerService);
    }
}