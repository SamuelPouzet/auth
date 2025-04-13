<?php

namespace SamuelPouzet\Auth\Entity;

use Doctrine\ORM\Mapping as ORM;
use SamuelPouzet\Auth\Interface\UserInterface;

#[ORM\Entity(readOnly: false)]
#[ORM\Table(name: 'user')]
class User implements UserInterface
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'integer', options: ['unsigned' => true])]
    protected int $id;

    #[ORM\Column(name: 'login', type: 'string', length: 100, unique: true, nullable: false)]
    protected string $login;

    #[ORM\Column(name: 'password', type: 'string', length: 250, unique: true, nullable: false)]
    protected string $password;

    #[ORM\Column(name: 'mail', type: 'string', length: 200, nullable: false)]
    protected string $mail;

    #[ORM\Column(name: 'date_created', type: 'datetime', nullable: false)]
    protected \DateTime $date_created;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;
        return $this;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTime $date_created): static
    {
        $this->date_created = $date_created;
        return $this;
    }
}
