<?php

namespace SamuelPouzet\Auth\Service\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\MailerService;

class MailerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): MailerService
    {
        $config = $container->get('config');
        if(! isset($config['mailer'])) {
            throw new ServiceNotCreatedException('Mailer config not found');
        }

        $email = new PHPMailer(true);

        return new MailerService($email, $config['mailer']);
    }
}