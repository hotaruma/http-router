<?php

declare(strict_types=1);

namespace Hotaruma\Tests\DataProvider;

use Hotaruma\HttpRouter\Enum\{AdditionalMethod, HttpMethod};
use Hotaruma\HttpRouter\Interface\PatternRegistry\PatternRegistryInterface;

class RouteMatcherDataProvider
{
    /**
     * @return array<mixed>
     */
    public static function matchRouteByHttpMethodDataProvider(): array
    {
        return [
            [[HttpMethod::GET, HttpMethod::POST], HttpMethod::GET, true],
            [[HttpMethod::GET], HttpMethod::POST, false],
            [[AdditionalMethod::ANY], HttpMethod::POST, true],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function matchRouteByRegexDataProvider(): array
    {
        return [
            ['/users/{id}/{page}/', [], '/users/123/page/new/', null],
            ['/use$rs/{id}/{page}/', [], '/use$rs/123/page/', ['id' => '123', 'page' => 'page']],
            [
                '/users/{id}/{page}/{idd}-{iddd}/',
                ['id' => '\d+', 'idd' => '\d+', 'iddd' => '\d+', 'page' => 'page|new-page'],
                '/users/123/new-page/321-3211/',
                ['id' => '123', 'page' => 'new-page', 'idd' => '321', 'iddd' => '3211',]
            ],
            [
                '/users/{id}/{page}/',
                ['id' => '\D+', 'page' => '\d+'],
                '/users/123/page/',
                null
            ],

            ['/users/{id:int}/{page}/', [], '/users/123/page/', ['id' => '123', 'page' => 'page']],
            ['/users/{id:int}/{page}/', [], '/users/qwe/page/', null],
            ['/users/{id:int}/{page}/', ['id' => '\w+'], '/users/qwe/page/', ['id' => 'qwe', 'page' => 'page']],

            ['/users/{id:\d+}/{page}/', [], '/users/123/page/', ['id' => '123', 'page' => 'page']],
            ['/users/{slug:[A-Za-z0-9-_]+}/{page}/', [], '/users/qwe/page/', ['slug' => 'qwe', 'page' => 'page']],
            ['/users/{id:\d+}/{page}/', [], '/users/qwe/page/', null],

            [
                '/users/{id}/{page}/{idd}-{iddd}/',
                ['id' => 'int', 'idd' => 'int', 'iddd' => 'int', 'page' => 'page|new-page'],
                '/users/123/new-page/321-3211/',
                ['id' => '123', 'page' => 'new-page', 'idd' => '321', 'iddd' => '3211',]
            ],
            [
                '/users/{id}/{page}/',
                ['id' => '\D+', 'page' => 'int'],
                '/users/123/page/',
                null
            ],
        ];
    }
}
