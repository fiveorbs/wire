<?php

declare(strict_types=1);

namespace Conia\Wire;

use Attribute;
use Conia\Wire\Exception\WireException;

/** @psalm-api */
#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class Inject
{
    public array $meta;

    public function __construct(
        public mixed $value,
        public ?Type $type = null,
        mixed ...$meta,
    ) {
        if (count($meta) > 0) {
            if (is_int(array_key_first($meta))) {
                throw new WireException('Meta arguments for Inject must be named arguments');
            }
        }

        $this->meta = $meta;
    }
}
