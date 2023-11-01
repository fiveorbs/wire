<?php

declare(strict_types=1);

namespace Conia\Resolver\Tests;

use Conia\Resolver\Resolver;
use Conia\Resolver\Tests\Fixtures\TestClass;
use Conia\Resolver\Tests\Fixtures\TestClassApp;
use Conia\Resolver\Tests\Fixtures\TestClassConstructor;
use Conia\Resolver\Tests\Fixtures\TestClassContainerArgs;
use Conia\Resolver\Tests\Fixtures\TestClassDefault;
use Conia\Resolver\Tests\Fixtures\TestClassMultiConstructor;

/**
 * @internal
 */
final class ResolverTest extends TestCase
{
    public function testSimpleAutowiring(): void
    {
        $resolver = new Resolver($this->container());

        $this->assertInstanceOf(TestClassConstructor::class, $resolver->autowire(TestClassConstructor::class));
    }

    public function testAutowiringWithPartialArgs(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->autowire(TestClassMultiConstructor::class, ['name' => 'chuck', 'number' => 73]);

        $this->assertInstanceOf(TestClassMultiConstructor::class, $tc);
        $this->assertEquals('chuck', $tc->name);
        $this->assertEquals(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testAutowiringWithPartialArgsAndDefaultValues(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->autowire(TestClassDefault::class, ['number' => 73]);

        $this->assertInstanceOf(TestClassDefault::class, $tc);
        $this->assertEquals('default', $tc->name);
        $this->assertEquals(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testAutowiringWithSimpleFactoryMethod(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->autowire(TestClassContainerArgs::class, [], 'fromDefaults');

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
        $this->assertEquals('fromDefaults', $tc->app->app());
        $this->assertEquals('fromDefaults', $tc->test);
    }

    public function testAutowiringWithFactoryMethodAndArgs(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->autowire(TestClassContainerArgs::class, ['test' => 'passed', 'app' => 'passed'], 'fromArgs');

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
        $this->assertEquals('passed', $tc->app->app());
        $this->assertEquals('passed', $tc->test);
    }

    public function testAutowiringWithNonAssocArgsArray(): void
    {
        $resolver = new Resolver($this->container());
        $tc = $resolver->autowire(TestClassContainerArgs::class, [new TestClass('non assoc'), 'passed']);

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
        $tc = $resolver->autowire(TestClass::class);
        $args = $resolver->resolveCallableArgs($tc);

        $this->assertEquals('default', $args[0]);
        $this->assertEquals(13, $args[1]);
    }
}
