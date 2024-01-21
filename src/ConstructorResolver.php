<?php

declare(strict_types=1);

namespace Conia\Wire;

use ReflectionClass;

/** @psalm-api */
class ConstructorResolver
{
    use ResolvesAbstractFunctions;

    public function __construct(
        protected readonly CreatorInterface $creator,
    ) {
    }

    /** @psalm-param ReflectionClass|class-string $class */
    public function resolve(
        ReflectionClass|string $class,
        array $predefinedArgs = [],
        array $predefinedTypes = [],
        ?callable $injectCallback = null,
    ): array {
        $rcls = is_string($class) ? new ReflectionClass($class) : $class;
        $constructor = $rcls->getConstructor();

        if ($constructor) {
            return $this->resolveArgs($constructor, $predefinedArgs, $predefinedTypes, $injectCallback);
        }

        return $predefinedArgs;
    }

    public function creator(): CreatorInterface
    {
        return $this->creator;
    }
}
