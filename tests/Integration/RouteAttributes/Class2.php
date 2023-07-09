<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Integration\RouteAttributes;

use Hotaruma\HttpRouter\Attribute\Route;
use Hotaruma\HttpRouter\Attribute\RouteGroup;
use Hotaruma\HttpRouter\Enum\HttpMethod;

#[RouteGroup(pathPrefix: '/home', methods: HttpMethod::POST, defaults: ['id' => '\d+'])]
class Class2
{
    #[Route('/route2')]
    #[Route('/route2')]
    public function route2(): void
    {
    }
}
