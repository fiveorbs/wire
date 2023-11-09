<?php

declare(strict_types=1);

namespace Conia\Wire;

class InjectedString
{
    public static function value(CreatorInterface $creator, string $value): mixed
    {
        $container = $creator->container();

        if ($container?->has($value)) {
            return $container->get($value);
        }

        if (class_exists($value)) {
            return $creator->create($value);
        }

        return $value;
    }
}
