<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\Creator;
use FiveOrbs\Wire\Tests\Fixtures\Container;
use FiveOrbs\Wire\Tests\Fixtures\WireizedContainer;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	public function __construct(?string $name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	public function container(): Container
	{
		$container = new Container();
		$container->add(Container::class, $container);

		return $container;
	}

	public function wireContainer(): WireizedContainer
	{
		$container = new WireizedContainer();
		$container->add(Container::class, $container);

		return $container;
	}

	public function creator(): Creator
	{
		return new Creator($this->container());
	}

	public function throws(string $exception, string $message = null): void
	{
		$this->expectException($exception);

		if ($message) {
			$this->expectExceptionMessage($message);
		}
	}
}
