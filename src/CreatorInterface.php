<?php

declare(strict_types=1);

namespace Conia\Wire;

use Psr\Container\ContainerInterface as Container;

interface CreatorInterface
{
    /** @psalm-param class-string $class */
    public function create(
        string $class,
        array $predefinedArgs = [],
        array $adhoc = [],
        ?string $constructor = null
    ): object;

    public function container(): ?Container;
}
