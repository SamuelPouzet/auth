<?php

namespace SamuelPouzet\Auth\Entity;

abstract class AbstractEntity
{
    public function getArrayCopy(): array
    {
        return get_object_vars($this);
    }
}
