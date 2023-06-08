<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interfaces\RouterExceptionInterface;
use InvalidArgumentException;

class RouteInvalidArgument extends InvalidArgumentException implements RouterExceptionInterface
{
}
