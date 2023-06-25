<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Collection;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use SplObjectStorage;

/**
 * @template-covariant TItems of RouteInterface
 * @template TValue
 *
 * @extends SplObjectStorage<RouteInterface, TValue>
 */
class RouteSplObjectStorage extends SplObjectStorage
{
}
