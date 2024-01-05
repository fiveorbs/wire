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
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(TestClassConstructor::class);

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame('default', $args[1]);
    }

    public function testGetConstructorArgsWithPredefinedParams(): void
    {
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(
            TestClassConstructor::class,
            predefinedArgs: ['value' => 'predefined'],
        );

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame('predefined', $args[1]);
    }

    public function testGetConstructorArgsWithAdhocEntries(): void
    {
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(TestClassConstructor::class);
        $args = $resolver->resolve(
            TestClassConstructor::class,
            adhocEntries: ['string' => 'adhoc'],
        );

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame('adhoc', $args[1]);
    }
}
