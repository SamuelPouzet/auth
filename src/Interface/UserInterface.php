<?php

namespace SamuelPouzet\Auth\Interface;

interface UserInterface
{
    public function getLogin(): ?string;

    public function getPassword(): ?string;
}