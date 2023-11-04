<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Creator;
use Conia\Wire\FunctionResolver;
use Conia\Wire\Tests\Fixtures\TestContainer;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function container(): TestContainer
    {
        $container = new TestContainer();
        $container->add(TestContainer::class, $container);

        return $container;
    }

    public function creator(): Creator
    {
        return new Creator($this->container());
    }

    public function resolver(): FunctionResolver
    {
        $container = new TestContainer();

        return new FunctionResolver(new Creator($container), $container);
    }

    public function throws(string $exception, string $message = null): void
    {
        $this->expectException($exception);

        if ($message) {
            $this->expectExceptionMessage($message);
        }
    }
}
