<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use SamuelPouzet\Auth\Interface\Form\AuthFormInterface;
use SamuelPouzet\Auth\Exception\AlreadyconnectedException;
use SamuelPouzet\Auth\Exception\NotconnectedException;

class CredentialService
{
    public function __construct(
        protected readonly AuthenticationService $authenticationService,
        protected readonly IdentityService $identityService,
    ) {
    }

    public function authenticate(array $credentials): ?Result
    {
        if ($this->identityService->getUser()) {
            throw new AlreadyconnectedException('user already connected');
        }

        $adapter = $this->authenticationService->getAdapter();
        $adapter->setLogin($credentials['login']);
        $adapter->setPassword($credentials['password']);
        $response = $this->authenticationService->authenticate();
        return $response;
    }

    public function logout()
    {
        if (! $this->identityService->getUser()) {
            throw new NotconnectedException('No user connected');
        }

        $this->authenticationService->clearIdentity();
        $this->identityService->setUser(null);
    }
}
