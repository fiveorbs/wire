<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\CallableResolver;
use FiveOrbs\Wire\Inject;
use FiveOrbs\Wire\Tests\Fixtures\TestClass;
use FiveOrbs\Wire\Tests\Fixtures\TestClassUsingNested;

final class CallableResolverTest extends TestCase
{
    public function testGetClosureArgs(): void
    {
        $resolver = new CallableResolver($this->creator());
        $args = $resolver->resolve(function (Testclass $testobj, int $number = 13): string {
            return $testobj::class . (string)$number;
        });

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame(13, $args[1]);
    }

    public function testGetCallableObjectArgs(): void
    {
        $resolver = new CallableResolver($this->creator());
        $testobj = $this->creator()->create(TestClass::class);
        $args = $resolver->resolve($testobj);

        $this->assertSame('default', $args[0]);
        $this->assertSame(13, $args[1]);
    }

    public function testGetArgsWithPredefinedArgs(): void
    {
        $resolver = new CallableResolver($this->creator());
        $args = $resolver->resolve(
            fn (Testclass $testobj, int $number): string => $testobj::class . (string)$number,
            predefinedArgs: ['number' => 17],
        );

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame(17, $args[1]);
    }

    public function testGetArgsWithPredefinedTypes(): void
    {
        $resolver = new CallableResolver($this->creator());
        $args = $resolver->resolve(
            fn (Testclass $testobj, int $number): string => $testobj::class . (string)$number,
            predefinedTypes: ['int' => 23],
        );

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertSame(23, $args[1]);
    }

    public function testNestedClassesWithPredefinedAndInject(): void
    {
        $resolver = new CallableResolver($this->creator());
        $args = $resolver->resolve(
            [TestClassUsingNested::class, 'create'],
            injectCallback: function (Inject $inject): mixed {
                return $inject->value . ' ' . $inject->meta['id'];
            },
            predefinedTypes: ['string' => 'predefined-value'],
        );
        $result = TestClassUsingNested::create(...$args);

        $this->assertSame('callback injected id', $result->tcn->callback);
        $this->assertSame('predefined-value', $result->tcn->predefined->value);
    }
}