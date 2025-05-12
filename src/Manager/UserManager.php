<?php

namespace SamuelPouzet\Auth\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Auth\Entity\User;
use SamuelPouzet\Auth\Enumerations\UserStatusEnum;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Crypt\Crypt;

class UserManager
{

    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    public function addUser(array $data): UserInterface
    {
        $hash = new Crypt();

        $user = (new User())
            ->setLogin($data['login'])
            ->setPassword($hash->hash($data['password']))
            ->setEmail($data['email'])
            ->setDateCreated(new \DateTime())
        ;
        $this->generateToken($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function generateToken(UserInterface $user): void
    {
        $token = bin2hex(random_bytes(32));
        $user->setToken($token);
    }

    public function updateUser(UserInterface $user, array $data): void
    {
        if (!! $data['login'] && $user->getLogin() !== $data['login']) {
            $user->setLogin($data['login']);
        }

        if (!! $data['email'] && $user->getEmail() !== $data['email']) {
            $user->setEmail($data['email']);
        }

        $this->entityManager->flush();
    }

    public function updatePassword(UserInterface $user, array $data): void
    {
        $hash = new Crypt();
        $user->setPassword($hash->hash($data['password']));
        $this->entityManager->flush();
    }

    public function activateByToken(string $token): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['token' => $token]);
        if (! $user) {
            throw new \Exception('user token not found');
        }
        $user
            ->setStatus(UserStatusEnum::ACTIVE)
            ->setToken(null);
        $this->entityManager->flush();
    }

    public function refreshToken(UserInterface $user, bool $setNull = false): void
    {
        if ($setNull) {
            $user->setToken(null);
        } else {
            $this->generateToken($user);
        }
        $this->entityManager->flush();
    }

    public function getUserByLogin(string $login): ?UserInterface
    {
        return $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $login]);
    }

    public function getUserByToken(string $token): ?UserInterface
    {
        return $this->entityManager->getRepository(UserInterface::class)->findOneBy(['token' => $token]);
    }
}
