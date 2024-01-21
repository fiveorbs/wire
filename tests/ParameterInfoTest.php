<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\ParameterInfo;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
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
            'Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor::__construct(' .
            '..., Conia\Wire\Tests\Fixtures\TestClassApp|' .
            'Conia\Wire\Tests\Fixtures\TestClassRequest $param, ...)',
            ParameterInfo::info($param)
        );
    }

    public function testParameterInfoFunction(): void
    {
        $rfun = new ReflectionFunction(function (TestClassApp $app) {
            $app->debug();
        });
        $param = $rfun->getParameters()[0];

        $this->assertSame(
            'Conia\Wire\Tests\ParameterInfoTest::Conia\Wire\Tests\{closure}' .
            '(..., Conia\Wire\Tests\Fixtures\TestClassApp $app, ...)',
            ParameterInfo::info($param)
        );
    }
}
