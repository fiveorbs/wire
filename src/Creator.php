<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;
use Psr\Container\ContainerInterface as Container;
use ReflectionClass;
use ReflectionObject;
use Throwable;

/** @psalm-api */
final class Creator
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

            return $this->applyCallAttributes($resolver, $instance);
        } catch (Throwable $e) {
            throw new WireException(
                'Unresolvable: ' . $class . ' Details: ' . $e::class . ' ' . $e->getMessage()
            );
        }
    }

    private function applyCallAttributes(Resolver $resolver, object $instance): object
    {
        $callAttrs = (new ReflectionObject($instance))->getAttributes(Call::class);

        // See if the attribute itself has one or more Call attributes. If so,
        // resolve/autowire the arguments of the method it states and call it.
        foreach ($callAttrs as $callAttr) {
            $callAttr = $callAttr->newInstance();
            $methodToResolve = $callAttr->method;

            /** @psalm-var callable */
            $callable = [$instance, $methodToResolve];
            $args = $resolver->resolveCallableArgs($callable, $callAttr->args);
            $callable(...$args);
        }

        return $instance;
    }
}
