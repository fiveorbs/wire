<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;

class InjectedArray
{
    public static function value(Creator $creator, array $value): mixed
    {
        if (count($value) === 2 && array_is_list($value)) {
            return match($value[1]) {
                Inject::Literal => $value[0],
                Inject::Create => self::getObject($creator, $value[0]),
                Inject::Entry => self::getEntry($creator, $value[0]),
                Inject::Env => getenv($value[0]),
                default => $value,
            };
        }

        return $value;
    }

    protected static function getEntry(Creator $creator, string $value): mixed
    {
        $container = $creator->container();

        if (is_null($container)) {
            throw new WireException('No container available to resolve injected id "' . $value . '"!');
        }

        if (!is_string($value)) {
            throw new WireException('Container entry id must be a string "' . (string)$value . '"!');
        }

        return $container->get($value);
    }

    protected static function getObject(Creator $creator, mixed $value): mixed
    {
        if (!is_string($value) || !class_exists($value)) {
            throw new WireException('No valid class string "' . (string)$value . '"!');
        }

        return $creator->create($value);
    }
}
