<?php

namespace SamuelPouzet\Auth\Command;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitDefaultUserCommand extends Command
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly UserService $userService,
        protected array $defaultUser,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $iostream = new SymfonyStyle($input, $output);
        $iostream->info('Checking user already created.');

        $users = $this->entityManager->getRepository(UserInterface::class)->findAll();
        if (! empty($users)) {
            $iostream->error('Users already initialized.');
            return Command::FAILURE;
        }
        try {
            $this->userService->createUser($this->defaultUser, true);
        } catch (\Exception $exception) {
            $iostream->error($exception->getMessage());
            return Command::FAILURE;
        }
        $iostream->info('User admin succesfully created');
        return Command::SUCCESS;
    }
}
