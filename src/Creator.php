<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;
use Psr\Container\ContainerInterface as Container;
use ReflectionClass;
use ReflectionObject;
use Throwable;

/** @psalm-api */
class Creator
{
    protected FunctionResolver $resolver;

    public function __construct(protected readonly ?Container $container = null)
    {
        $this->resolver = new FunctionResolver($this, $container);
    }

    /** @psalm-param class-string $class */
    public function create(
        string $class,
        array $predefinedArgs = [],
        ?string $constructor = null
    ): object {
        $rc = new ReflectionClass($class);

        try {
            if ($constructor) {
                // Factory method
                $rm = $rc->getMethod($constructor);
                $args = $this->resolver->resolve($rm, $predefinedArgs);
                $instance = $rm->invoke(null, ...$args);
            } else {
                // Regular constructor
                $args = (new ConstructorResolver($this->resolver))->resolve($rc, $predefinedArgs);
                $instance = $rc->newInstance(...$args);
            }

            assert(is_object($instance));

            return $this->applyCallAttributes($instance);
        } catch (Throwable $e) {
            throw new WireException(
                'Unresolvable: ' . $class . ' Details: ' . $e::class . ' ' . $e->getMessage()
            );
        }
    }

    protected function applyCallAttributes(object $instance): object
    {
        $callAttrs = (new ReflectionObject($instance))->getAttributes(Call::class);

        // See if the attribute itself has one or more Call attributes. If so,
        // resolve/autowire the arguments of the method it states and call it.
        foreach ($callAttrs as $callAttr) {
            $callAttr = $callAttr->newInstance();
            $methodToResolve = $callAttr->method;

            /** @psalm-var callable */
            $callable = [$instance, $methodToResolve];
            $args = (new CallableResolver($this->resolver))->resolve($callable, $callAttr->args);
            $callable(...$args);
        }

        return $instance;
    }
}
