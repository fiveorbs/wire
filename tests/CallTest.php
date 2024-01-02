<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Call;
use Conia\Wire\Creator;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Tests\Fixtures\Container;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassCall;
use Conia\Wire\Tests\Fixtures\TestClassInject;
use Conia\Wire\Tests\Fixtures\TestClassRequest;
use Conia\Wire\Tests\TestCase;

final class CallTest extends TestCase
{
    public function testCallAttributes(): void
    {
        $creator = new Creator($this->container());
        $attr = $creator->create(TestClassCall::class);

        $this->assertInstanceOf(Container::class, $attr->container);
        $this->assertInstanceOf(TestClassApp::class, $attr->app);
        $this->assertInstanceOf(TestClassRequest::class, $attr->request);
        $this->assertSame('arg1', $attr->arg1);
        $this->assertSame('arg2', $attr->arg2);
    }

    public function testCallAttributeDoesNotAllowUnnamedArgs(): void
    {
        $this->throws(WireException::class, 'Arguments for Call');

        new Call('method', 'arg');
    }

    public function testInjectAndCallCombined(): void
    {
        $container = $this->container();
        $container->add('injected', new TestClassApp('injected'));
        $creator = new Creator($container);

        $obj = $creator->create(TestClassInject::class);

        $this->assertSame('injected', $obj->app->app());
        $this->assertSame('arg1', $obj->arg1);
        $this->assertSame(13, $obj->arg2);
        $this->assertInstanceOf(Container::class, $obj->container);
        $this->assertSame('Stringable extended', (string)$obj->tc);
        $this->assertSame('calledArg1', $obj->calledArg1);
        $this->assertSame(73, $obj->calledArg2);
    }
}
