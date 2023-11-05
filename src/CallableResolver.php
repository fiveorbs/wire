<?php

declare(strict_types=1);

namespace Conia\Wire;

use Closure;
use ReflectionFunction;

/** @psalm-api */
class CallableResolver
{
    use ResolvesAbstractFunctions;

    public function __construct(
        protected readonly CreatorInterface $creator,
    ) {
    }

    /** @psalm-param callable-array|callable $callable */
    public function resolve(array|callable $callable, array $predefinedArgs = []): array
    {
        $callable = Closure::fromCallable($callable);
        $rf = new ReflectionFunction($callable);

        return $this->resolveArgs($rf, $predefinedArgs);
    }

    protected function creator(): CreatorInterface
    {
        return $this->creator;
    }
}
