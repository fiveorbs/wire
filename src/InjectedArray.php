<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;

class InjectedArray
{
    public static function value(CreatorInterface $creator, array $value, array $predefinedTypes): mixed
    {
        if (count($value) === 2 && array_is_list($value) && $value[1] instanceof Type) {
            return match ($value[1]) {
                Type::Literal => $value[0],
                Type::Create => self::getObject($creator, $value[0]),
                Type::Entry => self::getEntry($creator, (string)$value[0], $predefinedTypes),
                Type::Env => self::getEnvVar($value[0]),
            };
        }

        return $value;
    }

    protected static function getEntry(CreatorInterface $creator, string $value, array $predefinedTypes): mixed
    {
        if (isset($predefinedTypes[$value])) {
            return $predefinedTypes[$value];
        }

        $container = $creator->container();

        if (is_null($container)) {
            throw new WireException('No container available to resolve injected id "' . $value . '"!');
        }

        return $container->get($value);
    }

    protected static function getObject(CreatorInterface $creator, mixed $value): mixed
    {
        if (!is_string($value) || !class_exists($value)) {
            throw new WireException('No valid class string "' . (string)$value . '"!');
        }

        return $creator->create($value);
    }

    protected static function getEnvVar(mixed $value): bool|string
    {
        if (!is_string($value)) {
            throw new WireException('Environment variable must be a string!');
        }

        return getenv($value);
    }
}
