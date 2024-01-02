<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\CallableResolver;
use Conia\Wire\Tests\Fixtures\TestClass;

final class CallableResolverTest extends TestCase
{
    public function testGetClosureArgs(): void
    {
        $resolver = new CallableResolver($this->creator());
        $args = $resolver->resolve(function (Testclass $tc, int $number = 13) {});

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame(13, $args[1]);
    }

    public function testGetCallableObjectArgs(): void
    {
        $resolver = new CallableResolver($this->creator());
        $tc = $this->creator()->create(TestClass::class);
        $args = $resolver->resolve($tc);

        $this->assertSame('default', $args[0]);
        $this->assertSame(13, $args[1]);
    }
}
