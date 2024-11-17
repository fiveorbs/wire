<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use Attribute;

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
        $this->meta = $meta;
    }
}