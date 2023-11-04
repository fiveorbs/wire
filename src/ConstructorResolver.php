<?php

declare(strict_types=1);

namespace Conia\Wire;

use Psr\Container\ContainerInterface as Container;
use ReflectionClass;

/** @psalm-api */
class ConstructorResolver
{
    public function __construct(
        protected readonly Creator $creator,
        protected readonly ?Container $container = null
    ) {
    }

    /** @psalm-param ReflectionClass|class-string $class */
    public function resolve(ReflectionClass|string $class, array $predefinedArgs = []): array
    {
        $rc = is_string($class) ? new ReflectionClass($class) : $class;
        $constructor = $rc->getConstructor();

        if ($constructor) {
            return (new FunctionResolver($this->creator, $this->container))
                ->resolve($constructor, $predefinedArgs);
        }

        return $predefinedArgs;
    }
}
