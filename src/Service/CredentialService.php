<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Form\Form;
use SamuelPouzet\Auth\Exception\AlreadyconnectedException;

class CredentialService
{
    public function __construct(
        protected readonly Form $form,
        protected readonly AuthenticationService $authenticationService,
        protected readonly IdentityService $identityService,
    )
    {
    }

    public function getForm(): Form
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
}