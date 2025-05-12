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

class CreateUserCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserService $userService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('login', 'l', InputOption::VALUE_OPTIONAL, 'New User Login');
        $this->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'New User password');
        $this->addOption('email', 'e', InputOption::VALUE_OPTIONAL, 'New User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $login = $input->getOption('login');

        if (! $login) {
            $login = $io->askQuestion(
                (new Question('Enter Login')),
            );
        }

        $user = $this->entityManager->getRepository(UserInterface::class)->findOneBy(['login' => $login]);
        if ($user) {
            $io->error('User with this login already exists.');
            return Command::INVALID;
        }

        try {
            $password = $input->getOption('password') ?? $io->askHidden('Enter Password');

            $email = $input->getOption('email') ?? $io->askQuestion(
                (new Question('Enter Email')),
            );


            $this->userService->createUser([
                'login' => $login,
                'password' => $password,
                'email' => $email,
            ]);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        $io->info('User created successfully.');
        return Command::SUCCESS;
    }
}
