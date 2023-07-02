<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Enum;

use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

enum AdditionalMethod: string implements RequestMethodInterface
{
    case ANY = 'ANY';
    case NULL = 'NULL';
}
