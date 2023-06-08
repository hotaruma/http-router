<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interfaces\RouterExceptionInterface;
use RuntimeException;

class RouteGenerateNotFoundException extends RuntimeException implements RouterExceptionInterface
{
}
