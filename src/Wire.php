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
}
