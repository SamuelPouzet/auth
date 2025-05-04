<?php

namespace SamuelPouzet\Auth\Service;

use SamuelPouzet\Auth\Interface\Form\UpdateUserFormInterface;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Manager\UserManager;

class UserService
{
    public function __construct(
        protected readonly UserFormInterface $userForm,
        protected readonly UpdateUserFormInterface $updateUserForm,
        protected readonly UserManager $userManager
    ) {
    }

    public function getUserForm(): UserFormInterface
    {
        return $this->userForm;
    }

    public function getUpdateUserForm(): UpdateUserFormInterface
    {
        return $this->updateUserForm;
    }

    public function createUser(array $post): void
    {
        $this->userForm->setData($post);
        if ($this->userForm->isValid()) {
            $data = $this->userForm->getData();
            $this->userManager->addUser($data);
        }
    }

    public function updateUser(UserInterface $user, array $post): void
    {
        $this->updateUserForm->setData($post);
        if ($this->updateUserForm->isValid()) {
            $data = $this->updateUserForm->getData();
            $this->userManager->updateUser($user, $data);
        }
    }

    public function updatePassword(UserInterface $user, array $post, bool $needsValidation = true): void
    {
        $this->userManager->updatePassword($user, $post);
    }
}
