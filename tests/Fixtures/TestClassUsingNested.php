<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

class TestClassUsingNested
{
    public readonly TestClassNested $tcn;

    public function __construct(TestClassNested $tcn)
    {
        $this->tcn = $tcn;
    }

    public static function create(TestClassNested $tcn): self
    {
        return new self($tcn);
    }
}
