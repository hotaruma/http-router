<?php

declare(strict_types=1);

namespace Tests\Integration\RouteAttributes;

use Hotaruma\HttpRouter\Attribute\Route;
use Hotaruma\HttpRouter\Enum\HttpMethod;

class Class1
{
    #[Route('/route1', methods: HttpMethod::POST)]
    public function route1(): void
    {
    }

    #[Route('/route2', methods: HttpMethod::POST)]
    public function route2(): void
    {
    }
}
