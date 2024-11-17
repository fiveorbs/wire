<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use Attribute;
use FiveOrbs\Wire\Exception\WireException;

/** @psalm-api */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class Call
{
    public array $args;

    public function __construct(public readonly string $method, mixed ...$args)
    {
        if (count($args) > 0) {
            if (is_int(array_key_first($args))) {
                throw new WireException('Arguments for Call must be named arguments');
            }
        }

        $this->args = $args;
    }
}