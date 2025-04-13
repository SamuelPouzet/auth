<?php

namespace SamuelPouzet\Auth\Service;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Authentication\AuthenticationServiceInterface;
use SamuelPouzet\Auth\Interface\UserInterface;

class IdentityService
{
    protected ?UserInterface $user;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected AuthenticationServiceInterface $authenticationService
    ) {
         $login = $authenticationService->getIdentity();

         $this->user = $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $login]);
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): IdentityService
    {
        $this->user = $user;
        return $this;
    }

    public function hasUser(): bool
    {
        return $this->user !== null;
    }
}
