<?php

namespace SamuelPouzet\Auth\View;

use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Service\IdentityService;

class CurrentUserHelper
{

    protected ?UserInterface $user = null;

    public function __construct(protected readonly IdentityService $identityService)
    {
    }

    public function __invoke(): ?UserInterface
    {
        if ($this->user) {
            return $this->user;
        }
        $this->user = $this->identityService->getUser();
        return $this->user;
    }

}
