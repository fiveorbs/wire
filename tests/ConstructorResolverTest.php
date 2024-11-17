<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\ConstructorResolver;
use FiveOrbs\Wire\Inject;
use FiveOrbs\Wire\Tests\Fixtures\TestClass;
use FiveOrbs\Wire\Tests\Fixtures\TestClassConstructor;
use FiveOrbs\Wire\Tests\Fixtures\TestClassInjectCallback;
use FiveOrbs\Wire\Tests\Fixtures\TestClassUsingNested;

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

	public function testNestedClassesWithPredefinedAndInject(): void
	{
		$resolver = new ConstructorResolver($this->creator());
		$args = $resolver->resolve(
			TestClassUsingNested::class,
			injectCallback: function (Inject $inject): mixed {
				return $inject->value . ' ' . $inject->meta['id'];
			},
			predefinedTypes: ['string' => 'predefined-value'],
		);
		$result = new TestClassUsingNested(...$args);

		$this->assertSame('callback injected id', $result->tcn->callback);
		$this->assertSame('predefined-value', $result->tcn->predefined->value);
	}
}
