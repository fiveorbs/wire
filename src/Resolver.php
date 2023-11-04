<?php

declare(strict_types=1);

namespace Conia\Wire;

use Closure;
use Conia\Wire\Exception\WireException;
use Psr\Container\ContainerInterface as Container;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

/** @psalm-api */
class Resolver
{
    public function __construct(
        protected readonly Creator $creator,
        protected readonly ?Container $container = null
    ) {
    }

    public function resolveParam(ReflectionParameter $param): mixed
    {
        $type = $param->getType();

        if ($type instanceof ReflectionNamedType) {
            $typeName = ltrim($type->getName(), '?');

            if ($this->container?->has($typeName)) {
                return $this->container->get($typeName);
            }

            if (class_exists($typeName)) {
                return $this->creator->create($typeName);
            }

            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            throw new WireException('Parameter not resolvable');
        }

        if ($type) {
            throw new WireException(
                "Cannot resolve union or intersection types. Source: \n" .
                    $this->getParamInfo($param)
            );
        }

        throw new WireException(
            "To be resolvable, classes must have fully typed constructor parameters. Source: \n" .
                $this->getParamInfo($param)
        );
    }

    public function getParamInfo(ReflectionParameter $param): string
    {
        $type = $param->getType();
        $rf = $param->getDeclaringFunction();
        $rc = null;

        if ($rf instanceof ReflectionMethod) {
            $rc = $rf->getDeclaringClass();
        }

        return ($rc ? $rc->getName() . '::' : '') .
            ($rf->getName() . '(..., ') .
            ($type ? (string)$type . ' ' : '') .
            '$' . $param->getName() . ', ...)';
    }

    /** @psalm-param callable-array|callable $callable */
    public function resolveCallableArgs(array|callable $callable, array $predefinedArgs = []): array
    {
        $callable = Closure::fromCallable($callable);
        $rf = new ReflectionFunction($callable);
        $predefinedArgs = array_merge($this->getInjectedArgs($rf), $predefinedArgs);

        return $this->resolveArgs($rf, $predefinedArgs);
    }

    /** @psalm-param ReflectionClass|class-string $class */
    public function resolveConstructorArgs(ReflectionClass|string $class, array $predefinedArgs = []): array
    {
        $rc = is_string($class) ? new ReflectionClass($class) : $class;
        $constructor = $rc->getConstructor();

        if ($constructor) {
            $combinedArgs = array_merge($this->getInjectedArgs($constructor), $predefinedArgs);

            return $this->resolveArgs($constructor, $combinedArgs);
        }

        return $predefinedArgs;
    }

    public function resolveArgs(
        ?ReflectionFunctionAbstract $rf,
        array $predefinedArgs = [],
    ): array {
        $args = [];

        if ($rf) {
            $parameters = $rf->getParameters();
            $countPredefined = count($predefinedArgs);

            if (array_is_list($predefinedArgs) && $countPredefined > 0) {
                // predefined args are not named, use them as they are
                $args = $predefinedArgs;
                $parameters = array_slice($parameters, $countPredefined);
            }

            foreach ($parameters as $param) {
                $name = $param->getName();

                if (isset($predefinedArgs[$name])) {
                    /** @psalm-var list<mixed> */
                    $args[] = $predefinedArgs[$name];
                } else {
                    /** @psalm-var list<mixed> */
                    $args[] = $this->resolveParam($param);
                }
            }
        }

        return $args;
    }

    protected function getInjectedArgs(ReflectionFunctionAbstract $rf): array
    {
        /** @psalm-var array<non-empty-string, mixed> */
        $result = [];
        $injectAttrs = $rf->getAttributes(Inject::class);

        foreach ($injectAttrs as $injectAttr) {
            $instance = $injectAttr->newInstance();

            /** @psalm-suppress MixedAssignment */
            foreach ($instance->args as $name => $value) {
                assert(is_string($name));

                if (is_string($value)) {
                    if ($this->container?->has($value)) {
                        $result[$name] = $this->container->get($value);
                    } elseif (class_exists($value)) {
                        $result[$name] = $this->creator->create($value);
                    } else {
                        $result[$name] = $value;
                    }
                } else {
                    $result[$name] = $value;
                }
            }
        }

        return $result;
    }
}
