<?php

namespace SamuelPouzet\Auth\Config;

use Application\Controller\IndexController;
use Application\Controller\LoginController;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Authentication\AuthenticationService;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use SamuelPouzet\Auth\Adapter\AuthAdapter;
use SamuelPouzet\Auth\Adapter\Factory\AuthAdapterFactory;
use SamuelPouzet\Auth\Command\CreateUserCommand;
use SamuelPouzet\Auth\Command\Factory\CreateUserCommandFactory;
use SamuelPouzet\Auth\Command\Factory\InitDefaultUserCommandFactory;
use SamuelPouzet\Auth\Command\Factory\UpdatePasswordCommandFactory;
use SamuelPouzet\Auth\Command\Factory\UpdateUserCommandFactory;
use SamuelPouzet\Auth\Command\InitDefaultUserCommand;
use SamuelPouzet\Auth\Command\UpdatePasswordCommand;
use SamuelPouzet\Auth\Command\UpdateUserCommand;
use SamuelPouzet\Auth\Entity\User;
use SamuelPouzet\Auth\Form\AuthForm;
use SamuelPouzet\Auth\Form\ReinitPasswordForm;
use SamuelPouzet\Auth\Form\ReloadPasswordForm;
use SamuelPouzet\Auth\Form\TokenForm;
use SamuelPouzet\Auth\Form\UpdateUserForm;
use SamuelPouzet\Auth\Form\UserForm;
use SamuelPouzet\Auth\Interface\Form\AuthFormInterface;
use SamuelPouzet\Auth\Interface\Form\ReinitPasswordFormInterface;
use SamuelPouzet\Auth\Interface\Form\ReloadPasswordFormInterface;
use SamuelPouzet\Auth\Interface\Form\TokenFormInterface;
use SamuelPouzet\Auth\Interface\Form\UpdateUserFormInterface;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Listener\AuthListener;
use SamuelPouzet\Auth\Manager\Factory\UserManagerFactory;
use SamuelPouzet\Auth\Manager\UserManager;
use SamuelPouzet\Auth\Plugins\CurrentUserPlugin;
use SamuelPouzet\Auth\Plugins\Factory\CurrentUserPluginFactory;
use SamuelPouzet\Auth\Plugins\Factory\FormPluginFactory;
use SamuelPouzet\Auth\Plugins\Factory\UserPluginFactory;
use SamuelPouzet\Auth\Plugins\FormPlugin;
use SamuelPouzet\Auth\Plugins\UserPlugin;
use SamuelPouzet\Auth\Service\AuthService;
use SamuelPouzet\Auth\Service\CredentialService;
use SamuelPouzet\Auth\Service\EmailService;
use SamuelPouzet\Auth\Service\Factory\AuthenticationServiceFactory;
use SamuelPouzet\Auth\Service\Factory\AuthServiceFactory;
use SamuelPouzet\Auth\Service\Factory\CredentialServiceFactory;
use SamuelPouzet\Auth\Service\Factory\EmailServiceFactory;
use SamuelPouzet\Auth\Service\Factory\FormServiceFactory;
use SamuelPouzet\Auth\Service\Factory\IdentityServiceFactory;
use SamuelPouzet\Auth\Service\Factory\MailerServiceFactory;
use SamuelPouzet\Auth\Service\Factory\UserServiceFactory;
use SamuelPouzet\Auth\Service\FormService;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Auth\Service\MailerService;
use SamuelPouzet\Auth\Service\UserService;
use SamuelPouzet\Auth\View\CurrentUserHelper;
use SamuelPouzet\Auth\View\Factory\CurrentUserHelperFactory;

return [
    'samuelpouzet' => [
        'form_resolver' => [
            AuthFormInterface::class => AuthForm::class,
            UserFormInterface::class => UserForm::class,
            TokenFormInterface::class => TokenForm::class,
            UpdateUserFormInterface::class => UpdateUserForm::class,
            ReinitPasswordFormInterface::class => reinitPasswordForm::class,
            ReloadPasswordFormInterface::class => ReloadPasswordForm::class,
        ],
        'auth' => [
            'default_user' => [
                'login' => 'admin',
                'password' => 'Secur1ty!',
                'email' => 'admin@exemple.com',
            ],
        ],
    ],
    'authentication' => [
        'permissive' => true,
        'access_filter' => [
            IndexController::class => [
                'index' => '*'
            ],
            LoginController::class => [
                'index' => '@'
            ],
        ]
    ],
    'listeners' => [
        'authListener' => AuthListener::class
    ],
    'service_manager' => [
        'factories' => [
            //adapters
            AuthAdapter::class => AuthAdapterFactory::class,
            //commands
            CreateUserCommand::class => CreateUserCommandFactory::class,
            InitDefaultUserCommand::class => InitDefaultUserCommandFactory::class,
            UpdatePasswordCommand::class => UpdatePasswordCommandFactory::class,
            UpdateUserCommand::class => UpdateUserCommandFactory::class,
            //listeners
            AuthListener::class => InvokableFactory::class,
            //managers
            UserManager::class => UserManagerFactory::class,
            // services
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthService::class => AuthServiceFactory::class,
            CredentialService::class => CredentialServiceFactory::class,
            EmailService::class => EmailServiceFactory::class,
            FormService::class => FormServiceFactory::class,
            IdentityService::class => IdentityServiceFactory::class,
            MailerService::class => MailerServiceFactory::class,
            UserService::class => UserServiceFactory::class,
        ],
    ],
    'mailer' => [
        'default' => [
            'options' => [
                'protocol' => 'smtp',
                'user' => 'example@gmail.com',
                'password' => 'password',
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'smtp_auth' => true,
                'debug' => SMTP::DEBUG_SERVER,
                'secure' => PHPMailer::ENCRYPTION_STARTTLS,
                'options' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ]
                ]
            ],
        ],
    ],
    'mailer_account' => [
        'default' => [
            'from' => 'gandalf@sampouzet.fr'
        ]
    ],
    'controller_plugins' => [
        'factories' => [
            CurrentUserPlugin::class => CurrentUserPluginFactory::class,
            FormPlugin::class => FormPluginFactory::class,
            UserPlugin::class => UserPluginFactory::class,
        ],
        'aliases' => [
            'getCurrentUser' => CurrentUserPlugin::class,
            'getForm' => FormPlugin::class,
            'getUser' => UserPlugin::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            CurrentUserHelper::class => CurrentUserHelperFactory::class,
        ],
        'aliases' => [
            'currentUser' => CurrentUserHelper::class,
        ]
    ],
    'session_containers' => [
        'Purple_auth'
    ],
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 1,
        'gc_maxlifetime' => 60 * 60 * 24 * 30,
        'save_path' => dirname(__DIR__, 1) . '/data/session',

    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    'caches' => [
        'default-cache' => [
            'adapter' => Filesystem::class,
            'options' => [
                'cache_dir' => dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache',
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [
                    ],
                ],
            ],
        ],
    ],
    'laminas-cli' => [
        'commands' => [
            'auth:init' => InitDefaultUserCommand::class,
            'auth:user:create' => CreateUserCommand::class,
            'auth:user:update' => UpdateUserCommand::class,
            'auth:user:password-update' => UpdatePasswordCommand::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'cache' => 'array',
                'paths' => [dirname(__DIR__, 1) . '/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    'SamuelPouzet\Auth\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    UserInterface::class => User::class,
                ]
            ],
        ],
    ],
];
