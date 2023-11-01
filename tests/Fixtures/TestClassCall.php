<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

use Conia\Wire\Call;
use Conia\Wire\Tests\Fixtures\TestContainer;

#[Call('method1'), Call('method2', arg2: 'arg2', arg1: 'arg1')]
class TestClassCall
{
    public ?TestContainer $container = null;
    public ?TestClassApp $app = null;
    public ?TestClassRequest $request = null;
    public string $arg1 = '';
    public string $arg2 = '';

    public function method1(TestContainer $container, TestClassApp $app): void
    {
        $this->container = $container;
        $this->app = $app;
    }

    public function method2(string $arg1, TestClassRequest $request, string $arg2): void
    {
        $this->request = $request;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }
}
