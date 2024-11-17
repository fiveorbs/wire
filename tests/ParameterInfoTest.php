<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\ParameterInfo;
use FiveOrbs\Wire\Tests\Fixtures\TestClassApp;
use FiveOrbs\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use ReflectionFunction;

#[CoversClass(ParameterInfo::class)]
final class ParameterInfoTest extends TestCase
{
	public function testParameterInfoClass(): void
	{
		$rcls = new ReflectionClass(TestClassUnionTypeConstructor::class);
		$constructor = $rcls->getConstructor();
		$param = $constructor->getParameters()[0];

		$this->assertSame(
			'FiveOrbs\Wire\Tests\Fixtures\TestClassUnionTypeConstructor::__construct(' .
			'..., FiveOrbs\Wire\Tests\Fixtures\TestClassApp|' .
			'FiveOrbs\Wire\Tests\Fixtures\TestClassRequest $param, ...)',
			ParameterInfo::info($param),
		);
	}

	public function testParameterInfoFunction(): void
	{
		$rfun = new ReflectionFunction(function (TestClassApp $app) {
			$app->debug();
		});
		$param = $rfun->getParameters()[0];

		$this->assertSame(
			'FiveOrbs\Wire\Tests\ParameterInfoTest::FiveOrbs\Wire\Tests\{closure}' .
			'(..., FiveOrbs\Wire\Tests\Fixtures\TestClassApp $app, ...)',
			ParameterInfo::info($param),
		);
	}
}
