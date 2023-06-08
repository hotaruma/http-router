<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Enums;

use Hotaruma\HttpRouter\Interfaces\Method;

enum AdditionalMethod: string implements Method
{
    case ANY = 'ANY';
}
