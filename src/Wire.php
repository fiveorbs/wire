<?php

declare(strict_types=1);

namespace Conia\Wire;

use Psr\Container\ContainerInterface as Container;

/** @psalm-api */
class Wire
{
    public static function creator(?Container $container = null): Creator
    {
        return new Creator($container);
    }

    public static function callableResolver(?Container $container = null): CallableResolver
    {
        return new CallableResolver(self::creator($container));
    }

    public static function constructorResolver(?Container $container = null): ConstructorResolver
    {
        return new ConstructorResolver(self::creator($container));
    }
}
