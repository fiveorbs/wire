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
        array $predefinedTypes = [],
        ?callable $injectCallback = null,
        string $constructor = '',
    ): object;

    public function container(): ?Container;
}
