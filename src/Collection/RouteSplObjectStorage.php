<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Collection;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use SplObjectStorage;

/**
 * @template TItems of RouteInterface
 * @template TValue
 *
 * @extends SplObjectStorage<TItems, TValue>
 */
class RouteSplObjectStorage extends SplObjectStorage
{
}
