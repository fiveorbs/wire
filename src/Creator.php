<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;
use Psr\Container\ContainerInterface as Container;
use ReflectionClass;
use Throwable;

/** @psalm-api */
class Creator
{
    public function __construct(protected readonly ?Container $container = null)
    {
    }

    /** @psalm-param class-string $class */
    public function create(
        string $class,
        array $predefinedArgs = [],
        ?string $constructor = null
    ): object {
        $rc = new ReflectionClass($class);
        $resolver = new Resolver($this, $this->container);

        try {
            if ($constructor) {
                // Factory method
                $rm = $rc->getMethod($constructor);
                $args = $resolver->resolveArgs($rm, $predefinedArgs);
                $instance = $rm->invoke(null, ...$args);
            } else {
                // Regular constructor
                $args = $resolver->resolveConstructorArgs($rc, $predefinedArgs);
                $instance = $rc->newInstance(...$args);
            }

            assert(is_object($instance));

            return $resolver->resolveCallAttributes($instance);
        } catch (Throwable $e) {
            throw new WireException(
                'Unresolvable: ' . $class . ' Details: ' . $e::class . ' ' . $e->getMessage()
            );
        }
    }
}
