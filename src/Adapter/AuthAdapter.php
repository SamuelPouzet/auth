<?php

namespace SamuelPouzet\Auth\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use SamuelPouzet\Auth\Enumerations\UserStatusEnum;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Crypt\Crypt;

class AuthAdapter implements AdapterInterface
{
    protected string $login;

    protected string $password;


    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    public function authenticate(): Result
    {
        $user = $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $this->getLogin()]);
        if (! $user) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['user not found']
            );
        }
        $status = $user->getStatus();

        if ($status !== UserStatusEnum::ACTIVE) {
            if ($status === UserStatusEnum::NOT_CONFIRMED) {
                return new Result(
                    Result::FAILURE_CREDENTIAL_INVALID,
                    null,
                    ['User is not active.']
                );
            } else {
                return new Result(
                    Result::FAILURE_CREDENTIAL_INVALID,
                    null,
                    ['User is retired.']
                );
            }
        }

        $crypt = new Crypt();
        if ($crypt->verify($this->getPassword(), $user->getPassword())) {
            return new Result(
                Result::SUCCESS,
                $user->getLogin(),
                ['Authenticated successfully.']
            );
        }
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']
        );
    }


    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
}
