<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

class TestClassMultiConstructor
{
    public function __construct(
        public readonly string $name,
        public readonly TestClass $testobj,
        public readonly int $number
    ) {
    }
}