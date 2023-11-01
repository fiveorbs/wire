<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Exception\WireException;
use Conia\Wire\Resolver;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassConstructor;
use Conia\Wire\Tests\Fixtures\TestClassContainerArgs;
use Conia\Wire\Tests\Fixtures\TestClassDefault;
use Conia\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassMultiConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUntypedConstructor;

/**
 * @internal
 */
final class ResolverTest extends TestCase
{
    public function testSimpleResolve(): void
    {
        $resolver = new Resolver($this->container());

        $this->assertInstanceOf(TestClassConstructor::class, $resolver->create(TestClassConstructor::class));
    }

    public function testResolveWithPartialArgs(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->create(TestClassMultiConstructor::class, ['name' => 'chuck', 'number' => 73]);

        $this->assertInstanceOf(TestClassMultiConstructor::class, $tc);
        $this->assertEquals('chuck', $tc->name);
        $this->assertEquals(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testResolveWithPartialArgsAndDefaultValues(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->create(TestClassDefault::class, ['number' => 73]);

        $this->assertInstanceOf(TestClassDefault::class, $tc);
        $this->assertEquals('default', $tc->name);
        $this->assertEquals(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testResolveWithSimpleFactoryMethod(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->create(TestClassContainerArgs::class, [], 'fromDefaults');

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
        $this->assertEquals('fromDefaults', $tc->app->app());
        $this->assertEquals('fromDefaults', $tc->test);
    }

    public function testResolveWithFactoryMethodAndArgs(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->create(TestClassContainerArgs::class, ['test' => 'passed', 'app' => 'passed'], 'fromArgs');

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
        $this->assertEquals('passed', $tc->app->app());
        $this->assertEquals('passed', $tc->test);
    }

    public function testResolveWithNonAssocArgsArray(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->create(TestClassContainerArgs::class, [new TestClass('non assoc'), 'passed']);

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals('non assoc', $tc->tc->value);
        $this->assertEquals('passed', $tc->test);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
    }

    public function testGetConstructorArgs(): void
    {
        $resolver = new Resolver($this->container());
        $args = $resolver->resolveConstructorArgs(TestClassConstructor::class);

        $this->assertInstanceOf(TestClass::class, $args[0]);
    }

    public function testGetClosureArgs(): void
    {
        $resolver = new Resolver($this->container());
        $args = $resolver->resolveCallableArgs(function (Testclass $tc, int $number = 13) {});

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertEquals(13, $args[1]);
    }

    public function testGetCallableObjectArgs(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->create(TestClass::class);
        $args = $resolver->resolveCallableArgs($tc);

        $this->assertEquals('default', $args[0]);
        $this->assertEquals(13, $args[1]);
    }

    public function testTryToResolveUnresolvable(): void
    {
        $this->throws(WireException::class, 'Unresolvable');

        $resolver = new Resolver();
        $resolver->create(TestClassDefault::class);
    }

    public function testRejectClassWithUntypedConstructor(): void
    {
        $this->throws(WireException::class, 'typed constructor parameters');

        $resolver = new Resolver();
        $resolver->create(TestClassUntypedConstructor::class);
    }

    public function testRejectClassWithUnsupportedConstructorUnionTypes(): void
    {
        $this->throws(WireException::class, 'union or intersection');

        $resolver = new Resolver();
        $resolver->create(TestClassUnionTypeConstructor::class);
    }

    public function testRejectClassWithUnsupportedConstructorIntersectionTypes(): void
    {
        $this->throws(WireException::class, 'union or intersection');

        $resolver = new Resolver();
        $resolver->create(TestClassIntersectionTypeConstructor::class);
    }
}
