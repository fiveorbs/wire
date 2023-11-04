<?php

declare(strict_types=1);

namespace Conia\Wire;

use Closure;
use Psr\Container\ContainerInterface as Container;
use ReflectionFunction;

/** @psalm-api */
class CallableResolver
{
    public function __construct(
        protected readonly Creator $creator,
        protected readonly ?Container $container = null
    ) {
    }

    /** @psalm-param callable-array|callable $callable */
    public function resolve(array|callable $callable, array $predefinedArgs = []): array
    {
        $callable = Closure::fromCallable($callable);
        $rf = new ReflectionFunction($callable);

        return (new FunctionResolver($this->creator, $this->container))
            ->resolve($rf, $predefinedArgs);
    }
}
