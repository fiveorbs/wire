<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;

class InjectedArray
{
    public static function value(CreatorInterface $creator, array $value): mixed
    {
        if (count($value) === 2 && array_is_list($value)) {
            return match ($value[1]) {
                Inject::Literal => $value[0],
                Inject::Create => self::getObject($creator, $value[0]),
                Inject::Entry => self::getEntry($creator, $value[0]),
                Inject::Env => self::getEnvVar($value[0]),
                default => $value,
            };
        }

        return $value;
    }

    protected static function getEntry(CreatorInterface $creator, mixed $value): mixed
    {
        $container = $creator->container();

        if (is_null($container)) {
            throw new WireException('No container available to resolve injected id "' . (string)$value . '"!');
        }

        if (!is_string($value)) {
            throw new WireException('No valid container entry id! Must be a string.');
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
