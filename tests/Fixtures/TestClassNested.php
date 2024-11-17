<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

use FiveOrbs\Wire\Inject;
use FiveOrbs\Wire\Type;

class TestClassNested
{
    public function __construct(
        #[Inject('callback', Type::Callback, id: 'injected id')]
        public readonly string $callback,
        public readonly TestClassPredefined $predefined,
    ) {
    }
}