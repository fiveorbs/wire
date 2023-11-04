<?php

declare(strict_types=1);

namespace Conia\Wire;

use ReflectionClass;

/** @psalm-api */
class ConstructorResolver
{
    public function __construct(
        protected readonly FunctionResolver $resolver,
    ) {
    }

    /** @psalm-param ReflectionClass|class-string $class */
    public function resolve(ReflectionClass|string $class, array $predefinedArgs = []): array
    {
        $rc = is_string($class) ? new ReflectionClass($class) : $class;
        $constructor = $rc->getConstructor();

        if ($constructor) {
            return $this->resolver->resolve($constructor, $predefinedArgs);
        }

        return $predefinedArgs;
    }
}
