<?php

declare(strict_types=1);

namespace Tests\Unit;

use Hotaruma\HttpRouter\Exception\PatternRegistryPatternNotFoundException;
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistry;
use PHPUnit\Framework\TestCase;

class PatternRegistryTest extends TestCase
{
    public function testGetPattern(): void
    {
        $patternRegistry = new PatternRegistry();

        $pattern = $patternRegistry->getPattern('int');
        $this->assertEquals('\d+', $pattern);

        $pattern = $patternRegistry->getPattern('slug');
        $this->assertEquals('[A-Za-z0-9-_]+', $pattern);
    }

    public function testGetPatternInvalid(): void
    {
        $this->expectException(PatternRegistryPatternNotFoundException::class);

        $patternRegistry = new PatternRegistry();
        $patternRegistry->getPattern('nonexistent');
    }

    public function testAddPattern(): void
    {
        $patternRegistry = new PatternRegistry();
        $patternRegistry->addPattern('custom', 'custom-pattern');

        $pattern = $patternRegistry->getPattern('custom');
        $this->assertEquals('custom-pattern', $pattern);
    }
}
