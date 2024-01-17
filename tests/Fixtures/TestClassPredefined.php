<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

class TestClassPredefined
{
    public function __construct(public readonly string $value)
    {
    }
}
