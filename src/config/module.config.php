<?php
namespace SamuelPouzet\Auth\Config;

use Application\Controller\IndexController;
use Application\Controller\LoginController;
use Application\Controller\ProtectedController;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Authentication\AuthenticationService;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;
use SamuelPouzet\Auth\Adapter\AuthAdapter;
use SamuelPouzet\Auth\Adapter\Factory\AuthAdapterFactory;
use SamuelPouzet\Auth\Entity\User;
use SamuelPouzet\Auth\Form\AuthForm;
use SamuelPouzet\Auth\Form\UpdateUserForm;
use SamuelPouzet\Auth\Form\UserForm;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Listener\AuthListener;
use SamuelPouzet\Auth\Manager\Factory\UserManagerFactory;
use SamuelPouzet\Auth\Manager\UserManager;
use SamuelPouzet\Auth\Plugins\CurrentUserPlugin;
use SamuelPouzet\Auth\Plugins\Factory\CurrentUserPluginFactory;
use SamuelPouzet\Auth\Plugins\Factory\UserPluginFactory;
use SamuelPouzet\Auth\Plugins\UserPlugin;
use SamuelPouzet\Auth\Service\AuthService;
use SamuelPouzet\Auth\Service\CredentialService;
use SamuelPouzet\Auth\Service\Factory\AuthenticationServiceFactory;
use SamuelPouzet\Auth\Service\Factory\AuthServiceFactory;
use SamuelPouzet\Auth\Service\Factory\CredentialServiceFactory;
use SamuelPouzet\Auth\Service\Factory\IdentityServiceFactory;
use SamuelPouzet\Auth\Service\Factory\UserServiceFactory;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Auth\Service\UserService;
use SamuelPouzet\Auth\View\CurrentUserHelper;
use SamuelPouzet\Auth\View\Factory\CurrentUserHelperFactory;

return [
    'samuelpouzet' => [
        'form' => [
            'authForm' => AuthForm::class,
            'userForm' => UserForm::class,
            'updateUserForm' => UpdateUserForm::class,
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
            ProtectedController::class => [
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
            //listeners
            AuthListener::class => InvokableFactory::class,
            //managers
            UserManager::class => UserManagerFactory::class,
            // services
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthService::class => AuthServiceFactory::class,
            CredentialService::class => CredentialServiceFactory::class,
            IdentityService::class => IdentityServiceFactory::class,
            UserService::class => UserServiceFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            UserPlugin::class => UserPluginFactory::class,
            CurrentUserPlugin::class => CurrentUserPluginFactory::class,
        ],
        'aliases' => [
            'getUser' => UserPlugin::class,
            'getCurrentUser' => CurrentUserPlugin::class,
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
