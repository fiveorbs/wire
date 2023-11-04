<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\CallableResolver;
use Conia\Wire\Tests\Fixtures\TestClass;

final class CallableResolverTest extends TestCase
{
    public function testGetClosureArgs(): void
    {
        $resolver = new CallableResolver($this->creator(), $this->container());
        $args = $resolver->resolve(function (Testclass $tc, int $number = 13) {});

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertEquals(13, $args[1]);
    }

    public function testGetCallableObjectArgs(): void
    {
        $resolver = new CallableResolver($this->creator(), $this->container());
        $tc = $this->creator()->create(TestClass::class);
        $args = $resolver->resolve($tc);

        $this->assertEquals('default', $args[0]);
        $this->assertEquals(13, $args[1]);
    }
}
