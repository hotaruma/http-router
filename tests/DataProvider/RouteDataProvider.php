<?php

declare(strict_types=1);

namespace Tests\DataProvider;

use stdClass;

class RouteDataProvider
{
    public static function actionDataProvider(): array
    {
        return [
            [[stdClass::class]],
        ];
    }

    public static function invalidActionDataProvider(): array
    {
        return [
            [[]],
        ];
    }

    public static function attributesDataProvider(): array
    {
        return [
            [['id' => '1', 'name' => 'John']],
            [['color' => 'red', 'size' => 'large']],
            [[]],
        ];
    }

    public static function invalidAttributesDataProvider(): array
    {
        return [
            [['id' => 1, 'name' => fn($a) => $a]],
        ];
    }

    public static function urlDataProvider(): array
    {
        return [
            ['/example/path/', '/example/path/'],
            ['example//path', '/example/path/'],
            ['//example//path///', '/example/path/'],
        ];
    }
}
