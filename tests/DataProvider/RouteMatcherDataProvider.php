<?php

declare(strict_types=1);

namespace Tests\DataProvider;

use Hotaruma\HttpRouter\Enum\{AdditionalMethod, HttpMethod};

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
                '/users/{id}/{page}/',
                ['id' => '\d+', 'page' => 'page|new-page'],
                '/users/123/new-page/',
                ['id' => '123', 'page' => 'new-page']
            ],
            [
                '/users/{id}/{page}/',
                ['id' => '\D+', 'page' => '\d+'],
                '/users/123/page/',
                null
            ],
        ];
    }
}
