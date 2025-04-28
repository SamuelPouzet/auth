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
        protected readonly AuthFormInterface $form,
        protected readonly AuthenticationService $authenticationService,
        protected readonly IdentityService $identityService,
    )
    {
    }

    public function getForm(): AuthFormInterface
    {
        return $this->form;
    }

    public function authenticate(array $credentials): ?Result
    {
        if ($this->identityService->getUser()) {
            throw new AlreadyconnectedException('user already connected');
        }

        $form = $this->getForm();
        $form->setData($credentials);

        if ($form->isValid()) {
            $data = $form->getData();
            $adapter = $this->authenticationService->getAdapter();
            $adapter->setLogin($data['login']);
            $adapter->setPassword($data['password']);
            $response = $this->authenticationService->authenticate();
            return $response;
        }
        return null;
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