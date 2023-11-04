<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Creator;
use Conia\Wire\Wire;

final class WireTest extends TestCase
{
    public function testCreatorFactory(): void
    {
        $creator = Wire::creator();

        $this->assertInstanceOf(Creator::class, $creator);
    }

    public function testCreatorFactoryWithContainer(): void
    {
        $creator = Wire::creator($this->container());

        $this->assertInstanceOf(Creator::class, $creator);
    }
}
