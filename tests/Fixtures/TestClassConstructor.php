<?php

declare(strict_types=1);

namespace Conia\Resolver\Tests\Fixtures;

class TestClassConstructor
{
    public function __construct(public readonly TestClass $tc)
    {
    }
}
