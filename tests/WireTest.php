<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests;

use FiveOrbs\Wire\CallableResolver;
use FiveOrbs\Wire\ConstructorResolver;
use FiveOrbs\Wire\Creator;
use FiveOrbs\Wire\Wire;
use Psr\Container\ContainerInterface as Container;

final class WireTest extends TestCase
{
    public function testCreatorFactory(): void
    {
        $creator = Wire::creator();

        $this->assertInstanceOf(Creator::class, $creator);
        $this->assertSame(null, $creator->creator()->container());
    }

    public function testCreatorFactoryWithContainer(): void
    {
        $container = $this->container();
        $creator = Wire::creator($container);

        $this->assertInstanceOf(Creator::class, $creator);
        $this->assertInstanceOf(Container::class, $creator->creator()->container());
    }

    public function testCallableResolverFactory(): void
    {
        $callableResolver = Wire::callableResolver();

        $this->assertInstanceOf(CallableResolver::class, $callableResolver);
        $this->assertSame(null, $callableResolver->creator()->container());
    }

    public function testCallableResolverFactoryWithContainer(): void
    {
        $container = $this->container();
        $callableResolver = Wire::callableResolver($container);

        $this->assertInstanceOf(CallableResolver::class, $callableResolver);
        $this->assertInstanceOf(Container::class, $callableResolver->creator()->container());
    }

    public function testConstructorResolverFactory(): void
    {
        $constructorResolver = Wire::constructorResolver();

        $this->assertInstanceOf(ConstructorResolver::class, $constructorResolver);
        $this->assertSame(null, $constructorResolver->creator()->container());
    }

    public function testConstructorResolverFactoryWithContainer(): void
    {
        $container = $this->container();
        $constructorResolver = Wire::constructorResolver($container);

        $this->assertInstanceOf(ConstructorResolver::class, $constructorResolver);
        $this->assertInstanceOf(Container::class, $constructorResolver->creator()->container());
    }
}