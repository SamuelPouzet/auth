<?php

namespace SamuelPouzet\Auth\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Auth\Entity\User;
use SamuelPouzet\Crypt\Crypt;

class UserManager
{

    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    public function addUser(array $data): void
    {
        $hash = new Crypt();

        $user = (new User())
            ->setLogin($data['login'])
            ->setPassword($hash->hash($data['password']))
            ->setMail($data['email'])
            ->setDateCreated(new \DateTime())
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}