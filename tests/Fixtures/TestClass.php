<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

class TestClass implements TestInterface
{
	public function __construct(public string $str = '') {}

	public function __toString(): string
	{
		return 'Stringable';
	}

	public function __invoke(string $name = 'default', int $number = 13): string
	{
		return $name . (string) $number;
	}

	public function test(): string
	{
		return '';
	}

	public function init(string $str): void
	{
		$this->str = $str;
	}
}
