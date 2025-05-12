<?php

namespace SamuelPouzet\Auth\Service;

use Laminas\View\Renderer\PhpRenderer;
use SamuelPouzet\Auth\Interface\UserInterface;

class EmailService
{
    public function __construct(
        protected PhpRenderer $renderer,
        protected MailerService $mailerService
    ) {
    }

    public function sendConfirmationEmail(UserInterface $user): void
    {
        $this->mailerService->setFrom('gandalf@sampouzet.fr');
        $this->mailerService->setTo($user->getEmail());
        $this->mailerService->setSubject('Confirmation de votre inscription');
        $this->mailerService->setBody($this->renderer->render('mail/activation', ['user' => $user]));
        $this->mailerService->send();
    }

    public function sendReinitialisationEmail(UserInterface $user): void
    {
        $this->mailerService->setFrom('gandalf@sampouzet.fr');
        $this->mailerService->setTo($user->getEmail());
        $this->mailerService->setSubject('Réinitialisation de votre mot de passe');
        $this->mailerService->setBody($this->renderer->render('mail/reinitialisation', ['user' => $user]));
        $this->mailerService->send();
    }
}