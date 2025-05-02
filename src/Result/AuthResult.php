<?php

namespace SamuelPouzet\Auth\Result;

use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Enumerations\AuthStatusEnum;

class AuthResult
{
    public function __construct(
        protected AuthStatusEnum $status,
        protected ?UserInterface $user = null,
        protected string $message = ''
    ) {
    }

    public function getStatus(): AuthStatusEnum
    {
        return $this->status;
    }

    public function setStatus(AuthStatusEnum $status): AuthResult
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): AuthResult
    {
        $this->user = $user;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): AuthResult
    {
        $this->message = $message;
        return $this;
    }

}