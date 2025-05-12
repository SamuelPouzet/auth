<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\View\Renderer\PhpRenderer;
use SamuelPouzet\Auth\Interface\Form\ReinitPasswordFormInterface;
use SamuelPouzet\Auth\Interface\Form\ReloadPasswordFormInterface;
use SamuelPouzet\Auth\Interface\Form\TokenFormInterface;
use SamuelPouzet\Auth\Interface\Form\UpdateUserFormInterface;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Manager\UserManager;

class UserService
{
    public function __construct(
        protected readonly UserFormInterface $userForm,
        protected readonly UpdateUserFormInterface $updateUserForm,
        protected readonly TokenFormInterface $tokenForm,
        protected readonly ReinitPasswordFormInterface $reinitPasswordForm,
        protected readonly ReloadPasswordFormInterface $reloadPasswordForm,
        protected readonly UserManager $userManager,
        protected readonly MailerService $mailerService,
        protected readonly PhpRenderer $renderer
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

    public function getTokenForm(): TokenFormInterface
    {
        return $this->tokenForm;
    }

    public function getReinitPasswordForm(): ReinitPasswordFormInterface
    {
        return $this->reinitPasswordForm;
    }

    public function getReloadPasswordForm(): ReloadPasswordFormInterface
    {
        return $this->reloadPasswordForm;
    }

    public function createUser(array $post): void
    {
        $this->userForm->setData($post);
        if ($this->userForm->isValid()) {
            $data = $this->userForm->getData();
            $user = $this->userManager->addUser($data);
            $this->sendConfirmationEmail($user);
        }
    }

    public function sendConfirmationEmail(UserInterface $user): void
    {
        $this->mailerService->setFrom('gandalf@sampouzet.fr');
        $this->mailerService->setTo($user->getEmail());
        $this->mailerService->setSubject('Confirmation de votre inscription');
        $this->mailerService->setBody($this->renderer->render('mail/activation', ['user' => $user]));
        $this->mailerService->send();
    }

    public function sendReinitialisationEmail(string $login): void
    {
        $user = $this->userManager->getUserByLogin($login);
        $this->userManager->refreshToken($user);

        $this->mailerService->setFrom('gandalf@sampouzet.fr');
        $this->mailerService->setTo($user->getEmail());
        $this->mailerService->setSubject('Confirmation de votre inscription');
        $this->mailerService->setBody($this->renderer->render('mail/reinitialisation', ['user' => $user]));
        $this->mailerService->send();
    }

    public function updateUser(UserInterface $user, array $post, bool $force = false): void
    {
        $this->updateUserForm->setData($post);
        if ($this->updateUserForm->isValid() || $force) {
            $data = $this->updateUserForm->getData();
            $this->userManager->updateUser($user, $data);
        }
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
            $token = $this->tokenForm->getData()['token'];
            return $this->userManager->getUserByToken($token);
        }
        return null;
    }

    public function updateToken(UserInterface $user, bool $setNull = false): void
    {
        $this->userManager->refreshToken($user, $setNull);
    }
}
