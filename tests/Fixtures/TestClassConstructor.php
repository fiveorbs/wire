<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

class TestClassConstructor
{
    public function __construct(
        public readonly TestClass $tc,
        public readonly string $value = 'default'
    ) {
    }
}
