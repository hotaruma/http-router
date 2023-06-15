<?php

declare(strict_types=1);

namespace Tests\DataProvider;

use Hotaruma\HttpRouter\Enum\HttpMethod;

class GroupRouteConfigDataProvider
{
    public static function pathMergeDataProvider(): array
    {
        return [
            ['', '/route/path/', '/route/path/'],
            ['/group/path/', '/route/path/', '/group/path/route/path/'],
        ];
    }

    public static function methodsMergeDataProvider(): array
    {
        return [
            [[], [HttpMethod::POST], [HttpMethod::POST]],
            [[HttpMethod::GET], [HttpMethod::POST], [HttpMethod::GET, HttpMethod::POST]],
            [[HttpMethod::GET, HttpMethod::DELETE], [HttpMethod::POST], [HttpMethod::GET, HttpMethod::DELETE, HttpMethod::POST]],
        ];
    }

    public static function nameMergeDataProvider(): array
    {
        return [
            ['', 'route.name', 'route.name'],
            ['group.name', 'route.name', 'group.name.route.name'],
        ];
    }

    public static function rulesMergeDataProvider(): array
    {
        return [
            [['page' => '\d+'], ['word' => '\s+'], ['page' => '\d+', 'word' => '\s+']],
            [['word' => '\s+', 'page' => '\d+'], ['page' => '\s+'], ['word' => '\s+', 'page' => '\s+']],
            [['page' => '\d+'], [], ['page' => '\d+']],
            [[], ['word' => '\s+'], ['word' => '\s+']],
            [[], [], []],
        ];
    }

    public static function defaultsMergeDataProvider(): array
    {
        return [
            [['page' => '1'], ['word' => '2'], ['word' => '2', 'page' => '1']],
            [['word' => '2', 'page' => '1'], ['word' => '1'], ['word' => '1', 'page' => '1']],
            [['page' => '1'], [], ['page' => '1']],
            [[], ['word' => '2'], ['word' => '2']],
            [[], [], []],
        ];
    }

    public static function middlewaresMergeDataProvider(): array
    {
        return [
            [['Middleware1'], ['Middleware2'], ['Middleware1', 'Middleware2']],
            [['Middleware1', 'Middleware2'], ['Middleware2'], ['Middleware1', 'Middleware2']],
            [['Middleware1'], [], ['Middleware1']],
            [[], ['Middleware2'], ['Middleware2']],
            [[], [], []],
        ];
    }
}
