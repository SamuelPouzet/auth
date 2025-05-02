<?php

namespace SamuelPouzet\Auth\Enumerations;

use Laminas\Http\Response;

enum AuthStatusEnum: int
{
    case GRANTED = Response::STATUS_CODE_200;
    case USER_REQUIRED = Response::STATUS_CODE_401;
    case DENIED = Response::STATUS_CODE_403;
    case ERROR = Response::STATUS_CODE_500;
}
