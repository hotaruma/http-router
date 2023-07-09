<?php

declare(strict_types=1);

namespace Hotaruma\Tests\DataProvider;

use stdClass;

class RouteDataProvider
{
    /**
     * @return array<mixed>
     */
    public static function actionDataProvider(): array
    {
        return [
            [[stdClass::class]],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function invalidActionDataProvider(): array
    {
        return [
            [[]],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function attributesDataProvider(): array
    {
        return [
            [['id' => '1', 'name' => 'John']],
            [['color' => 'red', 'size' => 'large']],
            [[]],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function invalidAttributesDataProvider(): array
    {
        return [
            [['id' => 1, 'name' => fn($a) => $a]],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function urlDataProvider(): array
    {
        return [
            ['/example/path/', '/example/path/'],
            ['example//path', '/example/path/'],
            ['//example//path///', '/example/path/'],
        ];
    }
}
