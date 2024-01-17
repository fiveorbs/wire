<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Creator;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Inject;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassConstructor;
use Conia\Wire\Tests\Fixtures\TestClassDefault;
use Conia\Wire\Tests\Fixtures\TestClassInjectCallback;
use Conia\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassMultiConstructor;
use Conia\Wire\Tests\Fixtures\TestClassObjectArgs;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUntypedConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUsingNested;

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
        $this->assertSame('chuck', $tc->name);
        $this->assertSame(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testResolveWithPredefinedTypes(): void
    {
        $creator = new Creator();
        $tc = $creator->create(
            TestClassObjectArgs::class,
            predefinedArgs: ['test' => 'teststring'],
            predefinedTypes: [TestClass::class => new TestClass('predefined')]
        );

        $this->assertInstanceOf(TestClassObjectArgs::class, $tc);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
        $this->assertSame('teststring', $tc->test);
        $this->assertSame('predefined', $tc->tc->str);
    }

    public function testResolveWithInjectCallback(): void
    {
        $creator = new Creator();
        $tc = $creator->create(
            TestClassInjectCallback::class,
            injectCallback: function (Inject $inject): mixed {
                return $inject->value . ' ' . $inject->meta['id'];
            },
        );

        $this->assertSame('callback injected id', $tc->callback);
    }

    public function testResolveConstructorWithInjectCallback(): void
    {
        $creator = new Creator();
        $tc = $creator->create(
            TestClassInjectCallback::class,
            constructor: 'create',
            injectCallback: function (Inject $inject): mixed {
                return $inject->value . ' ' . $inject->meta['id'];
            },
        );

        $this->assertSame('create callback injected id', $tc->callback);
    }

    public function testResolveWithPartialArgsAndDefaultValues(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassDefault::class, ['number' => 73]);

        $this->assertInstanceOf(TestClassDefault::class, $tc);
        $this->assertSame('default', $tc->name);
        $this->assertSame(73, $tc->number);
        $this->assertInstanceOf(TestClass::class, $tc->tc);
    }

    public function testResolveWithSimpleFactoryMethod(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassObjectArgs::class, constructor: 'fromDefaults');

        $this->assertSame(true, $tc->tc instanceof TestClass);
        $this->assertSame(true, $tc->app instanceof TestClassApp);
        $this->assertSame('fromDefaults', $tc->app->app());
        $this->assertSame('fromDefaults', $tc->test);
    }

    public function testResolveWithFactoryMethodAndArgs(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(
            TestClassObjectArgs::class,
            ['test' => 'passed', 'app' => 'passed'],
            constructor: 'fromArgs'
        );

        $this->assertSame(true, $tc->tc instanceof TestClass);
        $this->assertSame(true, $tc->app instanceof TestClassApp);
        $this->assertSame('passed', $tc->app->app());
        $this->assertSame('passed', $tc->test);
    }

    public function testResolveWithNonAssocArgsArray(): void
    {
        $creator = new Creator($this->container());
        $tc = $creator->create(TestClassObjectArgs::class, [new TestClass('non assoc'), 'passed']);

        $this->assertSame(true, $tc->tc instanceof TestClass);
        $this->assertSame('non assoc', $tc->tc->str);
        $this->assertSame('passed', $tc->test);
        $this->assertSame(true, $tc->app instanceof TestClassApp);
    }

    public function testResolveNestedClassesWithPredefinedAndInject(): void
    {
        $creator = new Creator($this->container());
        $tcun = $creator->create(
            TestClassUsingNested::class,
            injectCallback: function (Inject $inject): mixed {
                return $inject->value . ' construct ' . $inject->meta['id'];
            },
            predefinedTypes: ['string' => 'predefined-value'],
        );

        $this->assertSame('callback construct injected id', $tcun->tcn->callback);
        $this->assertSame('predefined-value', $tcun->tcn->predefined->value);
    }

    public function testResolveNestedClassesWithPredefinedInjectAndConstructor(): void
    {
        $creator = new Creator($this->container());
        $tcun = $creator->create(
            TestClassUsingNested::class,
            constructor: 'create',
            injectCallback: function (Inject $inject): mixed {
                return $inject->value . ' create ' . $inject->meta['id'];
            },
            predefinedTypes: ['string' => 'predefined-value'],
        );

        $this->assertSame('callback create injected id', $tcun->tcn->callback);
        $this->assertSame('predefined-value', $tcun->tcn->predefined->value);
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
