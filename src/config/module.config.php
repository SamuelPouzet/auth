<?php
namespace SamuelPouzet\Auth\Config;

use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;

return [
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
];
