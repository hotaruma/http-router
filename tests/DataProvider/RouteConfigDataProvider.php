<?php

declare(strict_types=1);

namespace Tests\DataProvider;

use Hotaruma\HttpRouter\Enum\HttpMethod;
use stdClass;

class RouteConfigDataProvider
{
    public static function rulesDataProvider(): array
    {
        return [
            [['page' => '\d+', 'word' => '\s+']],
            [['page' => '\d+']],
            [[]],
        ];
    }

    public static function invalidRulesDataProvider(): array
    {
        return [
            [[true]],
        ];
    }

    public static function defaultsDataProvider(): array
    {
        return [
            [['page' => '1', 'word' => '2']],
            [['page' => '1']],
            [[]],
        ];
    }

    public static function invalidDefaultsDataProvider(): array
    {
        return [
            [[true]],
        ];
    }

    public static function middlewaresDataProvider(): array
    {
        $fn = (fn($a) => $a)(...);
        return [
            [[stdClass::class, stdClass::class]],
            [[]],
            [$fn, [$fn]],
        ];
    }

    public static function pathDataProvider(): array
    {
        return [
            ['/example/path/', '/example/path/'],
            ['example//path', '/example/path/'],
            ['//example//path///', '/example/path/'],
        ];
    }

    public static function invalidPathDataProvider(): array
    {
        return [
            [''],
        ];
    }

    public static function nameDataProvider(): array
    {
        return [
            ['example.name', 'example.name'],
            ['.example.name.', 'example.name'],
            ['..example..name...', 'example.name'],
            ['', ''],
        ];
    }

    public static function methodsDataProvider(): array
    {
        return [
            [[HttpMethod::GET, HttpMethod::POST], [HttpMethod::GET, HttpMethod::POST]],
            [HttpMethod::GET, [HttpMethod::GET]],
        ];
    }

    public static function invalidMethodsDataProvider(): array
    {
        return [
            [[]],
            [[true]],
        ];
    }

    public static function pathMergeDataProvider(): array
    {
        return [
            ['/merge/path/', '/initial/path/', '/merge/path/initial/path/'],
        ];
    }

    public static function methodsMergeDataProvider(): array
    {
        return [
            [[HttpMethod::GET], [HttpMethod::POST], [HttpMethod::GET, HttpMethod::POST]],
            [[HttpMethod::GET, HttpMethod::DELETE], [HttpMethod::POST], [HttpMethod::GET, HttpMethod::DELETE, HttpMethod::POST]],
        ];
    }

    public static function nameMergeDataProvider(): array
    {
        return [
            ['merge.name', 'initial.name', 'merge.name.initial.name'],
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
