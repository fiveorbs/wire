<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use Psr\Container\ContainerInterface as Container;

interface WireContainer extends Container
{
	public function getDefinition(string $id): mixed;
}
