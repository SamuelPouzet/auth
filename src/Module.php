<?php

namespace SamuelPouzet\Auth;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;

class Module
{
    public function getConfig(): array
    {
        $config = include __DIR__ . '/config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $event): void
    {
        try {
            $sessionManager = $event->getApplication()->getServiceManager()->get(SessionManager::class);
            $this->forgetInvalidSession($sessionManager);
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    protected function forgetInvalidSession($sessionManager): void
    {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {
        }
        session_unset();
    }
}
