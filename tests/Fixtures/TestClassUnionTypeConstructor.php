<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

class TestClassUnionTypeConstructor
{
    public function __construct(public TestClassApp|TestClassRequest $param)
    {
    }
}
