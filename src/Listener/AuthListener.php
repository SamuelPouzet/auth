<?php

namespace SamuelPouzet\Auth\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SamuelPouzet\Auth\Service\AuthService;

class AuthListener
{
    protected array $listeners = [];

    public function attach(EventManagerInterface $events, int $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'authorize'],
            $priority
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function authorize(MvcEvent $event): void
    {
        try {
            $authService = $event->getApplication()->getServiceManager()->get(AuthService::class);
            if ($authService->authenticate($event)) {
                print('user is allowed');
                return;
            }
            print('user is not allowed');
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

}