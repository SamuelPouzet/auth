<?php

namespace SamuelPouzet\Auth\Plugins;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use SamuelPouzet\Auth\Interface\UserInterface;

class UserPlugin extends AbstractPlugin
{

    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(int $userId)
    {
        return $this->entityManager->getRepository(UserInterface::class)->find($userId);
    }
}
