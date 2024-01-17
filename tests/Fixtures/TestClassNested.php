<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

use Conia\Wire\Inject;
use Conia\Wire\Type;

class TestClassNested
{
    public function __construct(
        #[Inject('callback', Type::Callback, id: 'injected id')]
        public readonly string $callback,
        public readonly TestClassPredefined $predefined,
    ) {
    }
}
