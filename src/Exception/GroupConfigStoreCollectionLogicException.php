<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interface\Exception\RouterExceptionInterface;
use LogicException;

class GroupConfigStoreCollectionLogicException extends LogicException implements RouterExceptionInterface
{
}
