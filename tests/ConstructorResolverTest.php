<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\ConstructorResolver;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassConstructor;

final class ConstructorResolverTest extends TestCase
{
    public function testGetConstructorArgs(): void
    {
        $resolver = new ConstructorResolver($this->resolver());
        $args = $resolver->resolve(TestClassConstructor::class);

        $this->assertInstanceOf(TestClass::class, $args[0]);
    }
}
