<?php

declare(strict_types=1);

namespace Conia\Resolver\Tests\Fixtures;

class TestClassMultiConstructor
{
    public function __construct(
        public readonly string $name,
        public readonly TestClass $tc,
        public readonly int $number
    ) {
    }
}
