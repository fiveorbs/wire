<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Creator;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassConstructor;
use Conia\Wire\Tests\Fixtures\TestClassContainerArgs;
use Conia\Wire\Tests\Fixtures\TestClassDefault;
use Conia\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassMultiConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUntypedConstructor;

final class CreatorTest extends TestCase
{
    public function testSimpleResolve(): void
    {
        $creator = new Creator($this->container());

        $this->assertInstanceOf(TestClassConstructor::class, $creator->create(TestClassConstructor::class));
    }

    public function testResolveWithPartialArgs(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassMultiConstructor::class, ['name' => 'chuck', 'number' => 73]);

        $this->assertInstanceOf(TestClassMultiConstructor::class, $tc);
        $this->assertEquals('chuck', $tc->name);
        $this->assertEquals(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testResolveWithPartialArgsAndDefaultValues(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassDefault::class, ['number' => 73]);

        $this->assertInstanceOf(TestClassDefault::class, $tc);
        $this->assertEquals('default', $tc->name);
        $this->assertEquals(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testResolveWithSimpleFactoryMethod(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassContainerArgs::class, [], 'fromDefaults');

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
        $this->assertEquals('fromDefaults', $tc->app->app());
        $this->assertEquals('fromDefaults', $tc->test);
    }

    public function testResolveWithFactoryMethodAndArgs(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassContainerArgs::class, ['test' => 'passed', 'app' => 'passed'], 'fromArgs');

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
        $this->assertEquals('passed', $tc->app->app());
        $this->assertEquals('passed', $tc->test);
    }

    public function testResolveWithNonAssocArgsArray(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassContainerArgs::class, [new TestClass('non assoc'), 'passed']);

        $this->assertEquals(true, $tc->tc instanceof TestClass);
        $this->assertEquals('non assoc', $tc->tc->str);
        $this->assertEquals('passed', $tc->test);
        $this->assertEquals(true, $tc->app instanceof TestClassApp);
    }

    public function testTryToResolveUnresolvable(): void
    {
        $this->throws(WireException::class, 'Unresolvable');

        $creator = new Creator();
        $creator->create(TestClassDefault::class);
    }

    public function testRejectClassWithUntypedConstructor(): void
    {
        $this->throws(WireException::class, 'typed constructor parameters');

        $creator = new Creator();
        $creator->create(TestClassUntypedConstructor::class);
    }

    public function testRejectClassWithUnsupportedConstructorUnionTypes(): void
    {
        $this->throws(WireException::class, 'union or intersection');

        $creator = new Creator();
        $creator->create(TestClassUnionTypeConstructor::class);
    }

    public function testRejectClassWithUnsupportedConstructorIntersectionTypes(): void
    {
        $this->throws(WireException::class, 'union or intersection');

        $creator = new Creator();
        $creator->create(TestClassIntersectionTypeConstructor::class);
    }
}
