<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Resolver;
use Conia\Wire\Tests\Fixtures\TestClass;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassConstructor;
use Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use ReflectionClass;
use ReflectionFunction;

/**
 * @internal
 */
final class ResolverTest extends TestCase
{
    public function testGetConstructorArgs(): void
    {
        $resolver = new Resolver($this->creator(), $this->container());
        $args = $resolver->resolveConstructorArgs(TestClassConstructor::class);

        $this->assertInstanceOf(TestClass::class, $args[0]);
    }

    public function testGetClosureArgs(): void
    {
        $resolver = new Resolver($this->creator(), $this->container());
        $args = $resolver->resolveCallableArgs(function (Testclass $tc, int $number = 13) {});

        $this->assertInstanceOf(TestClass::class, $args[0]);
        $this->assertEquals(13, $args[1]);
    }

    public function testGetCallableObjectArgs(): void
    {
        $resolver = new Resolver($this->creator(), $this->container());
        $tc = $this->creator()->create(TestClass::class);
        $args = $resolver->resolveCallableArgs($tc);

        $this->assertEquals('default', $args[0]);
        $this->assertEquals(13, $args[1]);
    }

    public function testParameterInfoClass(): void
    {
        $rc = new ReflectionClass(TestClassUnionTypeConstructor::class);
        $c = $rc->getConstructor();
        $p = $c->getParameters()[0];
        $resolver = new Resolver($this->creator(), $this->container());
        $s = 'Conia\Wire\Tests\Fixtures\TestClassUnionTypeConstructor::__construct(' .
            '..., Conia\Wire\Tests\Fixtures\TestClassApp|' .
            'Conia\Wire\Tests\Fixtures\TestClassRequest $param, ...)';

        $this->assertEquals($s, $resolver->getParamInfo($p));
    }

    public function testParameterInfoFunction(): void
    {
        $rf = new ReflectionFunction(function (TestClassApp $app) {
            $app->debug();
        });
        $p = $rf->getParameters()[0];
        $resolver = new Resolver($this->creator(), $this->container());
        $s = 'Conia\Wire\Tests\ResolverTest::Conia\Wire\Tests\{closure}' .
            '(..., Conia\Wire\Tests\Fixtures\TestClassApp $app, ...)';

        $this->assertEquals($s, $resolver->getParamInfo($p));
    }
}
