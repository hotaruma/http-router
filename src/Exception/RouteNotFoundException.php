<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interfaces\RouterExceptionInterface;
use RuntimeException;

class RouteNotFoundException extends RuntimeException implements RouterExceptionInterface
{
}
