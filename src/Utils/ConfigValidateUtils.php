<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Utils;

use Hotaruma\HttpRouter\Exception\ConfigInvalidArgumentException;

trait ConfigValidateUtils
{
    /**
     * @param array<mixed> $data
     * @param string $message
     * @return void
     *
     * @throws ConfigInvalidArgumentException
     *
     * @phpstan-assert array<string, string> $data
     */
    public function stringStructure(
        array  $data,
        string $message
    ): void {
        foreach ($data as $name => $value) {
            if (!is_string($name) || !is_string($value)) {
                throw new ConfigInvalidArgumentException($message);
            }
        }
    }

    /**
     * @template T
     * @param array<mixed, object> $data
     * @param class-string<T> $className
     * @param string $message
     * @return void
     *
     * @throws ConfigInvalidArgumentException
     *
     * @phpstan-assert array<mixed, T> $data
     */
    public function itemsImplement(array $data, string $className, string $message): void
    {
        foreach ($data as $item) {
            if (!$item instanceof $className) {
                throw new ConfigInvalidArgumentException($message);
            }
        }
    }
}
