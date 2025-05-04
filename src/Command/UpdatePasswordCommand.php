<?php

namespace SamuelPouzet\Auth\Command;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdatePasswordCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserService $userService,
        ?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addOption('login', 'l', InputOption::VALUE_REQUIRED, 'User to update password');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $login = $input->getOption('login');
        $user = $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $login]);
        if (!$user) {
            $io->error('User with login "' . $login . '" not found');
            return Command::FAILURE;
        }

        $password = $io->askHidden('New password');

        $this->userService->updatePassword($user, ['password' => $password]);

        return Command::SUCCESS;
    }

}