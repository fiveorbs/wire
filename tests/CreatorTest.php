<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\Creator;
use FiveOrbs\Wire\Inject;
use FiveOrbs\Wire\Tests\Fixtures\TestClass;
use FiveOrbs\Wire\Tests\Fixtures\TestClassApp;
use FiveOrbs\Wire\Tests\Fixtures\TestClassConstructor;
use FiveOrbs\Wire\Tests\Fixtures\TestClassDefault;
use FiveOrbs\Wire\Tests\Fixtures\TestClassInjectCallback;
use FiveOrbs\Wire\Tests\Fixtures\TestClassMultiConstructor;
use FiveOrbs\Wire\Tests\Fixtures\TestClassObjectArgs;
use FiveOrbs\Wire\Tests\Fixtures\TestClassUsingNested;
use FiveOrbs\Wire\Tests\Fixtures\TestInterface;

final class CreatorTest extends TestCase
{
	public function testSimpleResolve(): void
	{
		$creator = new Creator($this->container());

		$this->assertInstanceOf(TestClassConstructor::class, $creator->create(TestClassConstructor::class));
	}

	public function testResolveWithPartialArgs(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(TestClassMultiConstructor::class, ['name' => 'chuck', 'number' => 73]);

		$this->assertInstanceOf(TestClassMultiConstructor::class, $testobj);
		$this->assertSame('chuck', $testobj->name);
		$this->assertSame(73, $testobj->number);
		$this->assertInstanceOf(TestClass::class, $testobj->testobj);
	}

	public function testResolveWithPredefinedTypes(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(
			TestClassObjectArgs::class,
			predefinedArgs: ['test' => 'teststring'],
			predefinedTypes: [TestClass::class => new TestClass('predefined')],
		);

		$this->assertInstanceOf(TestClassObjectArgs::class, $testobj);
		$this->assertInstanceOf(TestClass::class, $testobj->testobj);
		$this->assertSame('teststring', $testobj->test);
		$this->assertSame('predefined', $testobj->testobj->str);
	}

	public function testResolveWithInjectCallback(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(
			TestClassInjectCallback::class,
			injectCallback: function (Inject $inject): mixed {
				return $inject->value . ' ' . $inject->meta['id'];
			},
		);

		$this->assertSame('callback injected id', $testobj->callback);
	}

	public function testResolveConstructorWithInjectCallback(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(
			TestClassInjectCallback::class,
			constructor: 'create',
			injectCallback: function (Inject $inject): mixed {
				return $inject->value . ' ' . $inject->meta['id'];
			},
		);

		$this->assertSame('create callback injected id', $testobj->callback);
	}

	public function testResolveWithPartialArgsAndDefaultValues(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(TestClassDefault::class, ['number' => 73]);

		$this->assertInstanceOf(TestClassDefault::class, $testobj);
		$this->assertSame('default', $testobj->name);
		$this->assertSame(73, $testobj->number);
		$this->assertInstanceOf(TestClass::class, $testobj->testobj);
	}

	public function testResolveWithSimpleFactoryMethod(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(TestClassObjectArgs::class, constructor: 'fromDefaults');

		$this->assertSame(true, $testobj->testobj instanceof TestClass);
		$this->assertSame(true, $testobj->app instanceof TestClassApp);
		$this->assertSame('fromDefaults', $testobj->app->app());
		$this->assertSame('fromDefaults', $testobj->test);
	}

	public function testResolveWithFactoryMethodAndArgs(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(
			TestClassObjectArgs::class,
			['test' => 'passed', 'app' => 'passed'],
			constructor: 'fromArgs',
		);

		$this->assertSame(true, $testobj->testobj instanceof TestClass);
		$this->assertSame(true, $testobj->app instanceof TestClassApp);
		$this->assertSame('passed', $testobj->app->app());
		$this->assertSame('passed', $testobj->test);
	}

	public function testResolveWithNonAssocArgsArray(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(TestClassObjectArgs::class, [new TestClass('non assoc'), 'passed']);

		$this->assertSame(true, $testobj->testobj instanceof TestClass);
		$this->assertSame('non assoc', $testobj->testobj->str);
		$this->assertSame('passed', $testobj->test);
		$this->assertSame(true, $testobj->app instanceof TestClassApp);
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

	public function testResolveFromContainer(): void
	{
		$container = $this->container();
		$container->add(TestInterface::class, new TestClass('text'));
		$creator = new Creator($container);
		$testobj = $creator->create(TestInterface::class);

		$this->assertInstanceof(TestClass::class, $testobj);
		$this->assertSame('text', $testobj->str);
	}
}
