<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

class TestClassObjectArgs
{
	public function __construct(
		public readonly TestClass $testobj,
		public readonly string $test,
		public readonly ?TestClassApp $app = null,
	) {}

	public static function fromDefaults(): static
	{
		return new self(new TestClass(), 'fromDefaults', new TestClassApp('fromDefaults'));
	}

	public static function fromArgs(TestClass $testobj, string $test, string $app): static
	{
		return new self($testobj, $test, new TestClassApp($app));
	}
}
