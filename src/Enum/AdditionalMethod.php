<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Enum;

use Hotaruma\HttpRouter\Interface\Enum\Method;

enum AdditionalMethod: string implements Method
{
    case ANY = 'ANY';
}
