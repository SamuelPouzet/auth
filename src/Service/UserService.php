<?php

namespace SamuelPouzet\Auth\Service;

use SamuelPouzet\Auth\Interface\Form\TokenFormInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Manager\UserManager;

class UserService
{
    public function __construct(
        protected readonly EmailService $emailService,
        protected readonly UserManager $userManager,
        protected readonly FormService $formService,
    ) {
    }

    public function createUser(array $data): void
    {
            $user = $this->userManager->addUser($data);
            $this->emailService->sendConfirmationEmail($user);
    }

    public function updateUser(UserInterface $user, array $data): void
    {
        $this->userManager->updateUser($user, $data);
    }

    public function updatePassword(UserInterface $user, array $post, bool $needsValidation = true): void
    {
        $this->userManager->updatePassword($user, $post);
    }

    public function activateByToken(string $token): void
    {
        $this->userManager->activateByToken($token);
    }

    public function getUserByToken(TokenFormInterface $form): ?UserInterface
    {
        if ($form->isValid()) {
            $token = $form->getData()['token'];
            return $this->userManager->getUserByToken($token);
        }
        return null;
    }

    public function updateToken(UserInterface $user, bool $setNull = false): void
    {
        $this->userManager->refreshToken($user, $setNull);
    }
}
