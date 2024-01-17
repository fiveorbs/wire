<?php

declare(strict_types=1);

namespace Conia\Wire;

use Attribute;

/** @psalm-api */
#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class Inject
{
    public function __construct(
        public mixed $value,
        public ?Type $type = null,
    ) {
    }
}
