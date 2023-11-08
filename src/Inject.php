<?php

declare(strict_types=1);

namespace Conia\Wire;

use Attribute;
use Conia\Wire\Exception\WireException;

/** @psalm-api */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
class Inject
{
    public const Literal = 'Conia-Wire-Inject-Literal';
    public const Env = 'Conia-Wire-Inject-Env';
    public const Create = 'Conia-Wire-Inject-Create';
    public const Entry = 'Conia-Wire-Inject-Entry';

    public array $args;

    public function __construct(mixed ...$args)
    {
        if (count($args) > 0) {
            if (is_int(array_key_first($args))) {
                throw new WireException('Arguments for Inject must be named arguments');
            }
        }

        $this->args = $args;
    }
}
