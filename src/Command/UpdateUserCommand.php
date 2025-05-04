<?php

namespace SamuelPouzet\Auth\Command;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateUserCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserService            $userService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('login', 'l', InputOption::VALUE_OPTIONAL, 'Current User Login');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $login = $input->getOption('login');

        $user = $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $login]);

        if (!$user) {
            $io->error('User with this login doesn\'t exists.');
            return Command::INVALID;
        }

        try {
            $login = $io->askQuestion(
                (new Question(sprintf('Enter new Login [%1$s]', $user->getLogin()))),
            );

            $email = $io->askQuestion(
                (new Question(sprintf('Enter new Email [%1$s]', $user->getEmail())))
            );


            $this->userService->updateUser($user, [
                'login' => $login,
                'email' => $email,
            ], true);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->info('User updated successfully.');
        return Command::SUCCESS;
    }
}