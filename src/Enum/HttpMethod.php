<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Enum;

use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

enum HttpMethod: string implements RequestMethodInterface
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
