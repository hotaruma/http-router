<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interface\Exception\RouterExceptionInterface;
use RuntimeException;

class RouteDispatcherNotFoundException extends RuntimeException implements RouterExceptionInterface
{
}
