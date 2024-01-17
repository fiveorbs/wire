<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\ConstructorResolver;
use Conia\Wire\Inject;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassConstructor;
use Conia\Wire\Tests\Fixtures\TestClassInjectCallback;

final class ConstructorResolverTest extends TestCase
{
    public function testGetArgs(): void
    {
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(TestClassConstructor::class);

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame('default', $args[1]);
    }

    public function testGetArgsWithPredefinedArgs(): void
    {
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(
            TestClassConstructor::class,
            predefinedArgs: ['value' => 'predefined'],
        );

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame('predefined', $args[1]);
    }

    public function testGetArgsWithPredefinedTypes(): void
    {
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(TestClassConstructor::class);
        $args = $resolver->resolve(
            TestClassConstructor::class,
            predefinedTypes: ['string' => 'predefined'],
        );

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame('predefined', $args[1]);
    }

    public function testResolveWithInjectCallback(): void
    {
        $resolver = new ConstructorResolver($this->creator());
        $args = $resolver->resolve(
            TestClassInjectCallback::class,
            injectCallback: function (Inject $inject): mixed {
                return $inject->value . ' ' . $inject->meta['id'];
            },
        );

        $this->assertSame('callback injected id', $args[0]);
    }
}
