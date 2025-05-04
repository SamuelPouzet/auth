<?php

namespace SamuelPouzet\Auth\Enumerations;

enum UserStatusEnum: int
{
    case ACTIVE = 0;
    case NOT_CONFIRMED = 1;
    case RETIRED = 2;
}
