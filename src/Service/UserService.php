<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\Form\Form;
use SamuelPouzet\Auth\Manager\UserManager;

class UserService
{
    public function __construct(protected readonly Form $form, protected readonly UserManager $userManager)
    {
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function createUser(array $post): void
    {
        $this->form->setData($post);
        if ($this->form->isValid()) {
            $data = $this->form->getData();
            $this->userManager->addUser($data);
        }
    }
}