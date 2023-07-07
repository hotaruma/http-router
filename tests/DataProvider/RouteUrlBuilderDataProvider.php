<?php

declare(strict_types=1);

namespace Tests\DataProvider;

class RouteUrlBuilderDataProvider
{
    /**
     * @return array<mixed>
     */
    public static function buildDataProvider(): array
    {
        return [
            ['/n$ws/', [], [], [], '/n$ws/'],
            ['/news/{id}/', [], ['id' => '2'], [], '/news/2/'],
            ['/news/{id}/', [], [], ['id' => '2'], '/news/2/'],
            ['/news/{id}/', ['id' => '\d+'], ['id' => '2'], [], '/news/2/'],
            ['/news/{id}/', ['id' => '\d+'], [], ['id' => '2'], '/news/2/'],
            [
                '/news/{id}/{page}/',
                ['id' => '\d+', 'page' => '\w+'],
                ['id' => '2', 'page' => 'bob'],
                [],
                '/news/2/bob/'
            ],
            [
                '/news/{id}/{page}/',
                ['id' => '\d+', 'page' => '\w+'],
                [],
                ['id' => '2', 'page' => 'bob'],
                '/news/2/bob/'
            ],
            [
                '/news/{id}/{page}/',
                ['id' => '\d+', 'page' => '\w+'],
                ['id' => '1', 'page' => 'bob1'],
                ['id' => '2', 'page' => 'bob2'],
                '/news/2/bob2/'
            ],

            [
                '/news/{id}/{page}/',
                ['id' => 'int', 'page' => 'alpha'],
                ['id' => '2', 'page' => 'bob'],
                [],
                '/news/2/bob/'
            ],
            [
                '/news/{id:int}/{page:alpha}/',
                [],
                ['id' => '2', 'page' => 'bob'],
                [],
                '/news/2/bob/'
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function invalidBuildDataProvider(): array
    {
        return [
            ['/n$ws/{id}/', [], [], []],
            ['/n$ws/{id}/', ['id' => '[a-z]'], ['id' => '2'], []],
            ['/n$ws/{id}/', ['id' => '[a-z]'], [], ['id' => '2']],

            ['/n$ws/{id}/', ['id' => 'alpha'], ['id' => '2'], []],
            ['/n$ws/{id}/', ['id' => 'alpha'], [], ['id' => '2']],

            ['/n$ws/{id:[a-z]}/', [], ['id' => '2'], []],
            ['/n$ws/{id:[a-z]}/', [], [], ['id' => '2']],

            ['/n$ws/{id:alpha}/', [], ['id' => '2'], []],
            ['/n$ws/{id:alpha}/', [], [], ['id' => '2']],
        ];
    }
}
