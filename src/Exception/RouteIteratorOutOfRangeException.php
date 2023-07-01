<?php

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interface\Exception\RouterExceptionInterface;
use OutOfRangeException;

class RouteIteratorOutOfRangeException extends OutOfRangeException implements RouterExceptionInterface
{
}
