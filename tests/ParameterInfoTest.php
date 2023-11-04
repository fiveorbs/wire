<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\ParameterInfo;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use ReflectionClass;
use ReflectionFunction;

final class ParameterInfoTest extends TestCase
{
    public function testParameterInfoClass(): void
    {
        $rc = new ReflectionClass(TestClassUnionTypeConstructor::class);
        $c = $rc->getConstructor();
        $p = $c->getParameters()[0];
        $s = 'Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor::__construct(' .
            '..., Conia\Wire\Tests\Fixtures\TestClassApp|' .
            'Conia\Wire\Tests\Fixtures\TestClassRequest $param, ...)';

        $this->assertEquals($s, ParameterInfo::info($p));
    }

    public function testParameterInfoFunction(): void
    {
        $rf = new ReflectionFunction(function (TestClassApp $app) {
            $app->debug();
        });
        $p = $rf->getParameters()[0];
        $s = 'Conia\Wire\Tests\ParameterInfoTest::Conia\Wire\Tests\{closure}' .
            '(..., Conia\Wire\Tests\Fixtures\TestClassApp $app, ...)';

        $this->assertEquals($s, ParameterInfo::info($p));
    }
}
