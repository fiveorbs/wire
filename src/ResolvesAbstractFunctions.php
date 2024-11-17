<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use FiveOrbs\Wire\Exception\WireException;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
use ReflectionParameter;

/** @psalm-api */
trait ResolvesAbstractFunctions
{
    abstract protected function creator(): CreatorInterface;

    protected function resolveArgs(
        ReflectionFunctionAbstract $rfn,
        array $predefinedArgs,
        array $predefinedTypes,
        ?callable $injectCallback,
    ): array {
        $combinedArgs = array_merge(
            $this->resolveInjectedArgs($rfn, $predefinedTypes, $injectCallback),
            $predefinedArgs
        );

        $args = [];
        $parameters = $rfn->getParameters();
        $countPredefined = count($combinedArgs);

        if (array_is_list($combinedArgs) && $countPredefined > 0) {
            // predefined args are not named, use them as they are
            $args = $combinedArgs;
            $parameters = array_slice($parameters, $countPredefined);
        }

        foreach ($parameters as $param) {
            $name = $param->getName();

            if (isset($combinedArgs[$name])) {
                /** @psalm-var list<mixed> */
                $args[] = $combinedArgs[$name];
            } else {
                /** @psalm-var list<mixed> */
                $args[] = $this->resolveParam($param, $predefinedTypes, $injectCallback);
            }
        }

        return $args;
    }

    protected function resolveParam(
        ReflectionParameter $param,
        array $predefinedTypes,
        ?callable $injectCallback
    ): mixed {
        $type = $param->getType();

        if ($type instanceof ReflectionNamedType) {
            $creator = $this->creator();
            $container = $creator->container();
            $typeName = ltrim($type->getName(), '?');

            if (isset($predefinedTypes[$typeName])) {
                return $predefinedTypes[$typeName];
            }

            if ($container?->has($typeName)) {
                return $container->get($typeName);
            }

            if (class_exists($typeName)) {
                return $creator->create($typeName, predefinedTypes: $predefinedTypes, injectCallback: $injectCallback);
            }

            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            throw new WireException('Parameter not resolvable');
        }

        if ($type) {
            throw new WireException(
                "Cannot resolve union or intersection types. Source: \n" .
                    ParameterInfo::info($param)
            );
        }

        throw new WireException(
            "To be resolvable, classes must have fully typed constructor parameters. Source: \n" .
                ParameterInfo::info($param)
        );
    }

    /** @return array<non-empty-string, mixed> */
    protected function resolveInjectedArgs(
        ReflectionFunctionAbstract $rfn,
        array $predefinedTypes,
        ?callable $injectCallback,
    ): array {
        /** @var array<non-empty-string, mixed> */
        $result = [];

        foreach ($rfn->getParameters() as $param) {
            $injectAttr = $param->getAttributes(Inject::class)[0] ?? null;

            if ($injectAttr) {
                $instance = $injectAttr->newInstance();
                /** @psalm-suppress MixedAssignment */
                $result[$param->name] = Injected::value(
                    $instance,
                    $this->creator(),
                    $predefinedTypes,
                    $injectCallback
                );
            }
        }

        return $result;
    }
}