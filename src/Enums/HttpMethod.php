<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Enums;

use Hotaruma\HttpRouter\Interfaces\Method;

enum HttpMethod: string implements Method
{
    case CONNECT = 'CONNECT';
    case DELETE = 'DELETE';
    case GET = 'GET';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case PATCH = 'PATCH';
    case POST = 'POST';
    case PUT = 'PUT';
    case TRACE = 'TRACE';
}
