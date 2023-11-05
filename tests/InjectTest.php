<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\CallableResolver;
use Conia\Wire\ConstructorResolver;
use Conia\Wire\Creator;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Inject;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassInject;
use Conia\Wire\Tests\Fixtures\TestContainer;
use Conia\Wire\Tests\TestCase;

final class InjectTest extends TestCase
{
    public function testInjectClosureWithAttribute(): void
    {
        $container = $this->container();
        $container->add('injected', new TestClassApp('injected'));
        $creator = new Creator($container);
        $resolver = new CallableResolver($creator);

        $func = #[Inject(name: 'Chuck', app: 'injected')] function (
            TestContainer $r,
            TestClassApp $app,
            string $name
        ): array {
            return [$app->app, $name, $r];
        };

        $result = $func(...$resolver->resolve($func));

        $this->assertEquals('injected', $result[0]);
        $this->assertEquals('Chuck', $result[1]);
        $this->assertInstanceOf(TestContainer::class, $result[2]);
    }

    public function testInjectConstructorWithAttribute(): void
    {
        $container = $this->container();
        $container->add('injected', new TestClassApp('injected'));
        $creator = new Creator($container);
        $resolver = new ConstructorResolver($creator);

        $args = $resolver->resolve(TestClassInject::class);
        $obj = new TestClassInject(...$args);

        $this->assertEquals('injected', $obj->app->app());
        $this->assertEquals('arg1', $obj->arg1);
        $this->assertEquals(13, $obj->arg2);
        $this->assertInstanceOf(TestContainer::class, $obj->container);
        $this->assertEquals('Stringable extended', (string)$obj->tc);
    }

    public function testInjectAttributeDoesNotAllowUnnamedArgs(): void
    {
        $this->throws(WireException::class, 'Arguments for Inject');

        new Inject('arg');
    }
}
