<?php

declare(strict_types=1);

namespace FiveOrbs\Wire;

use Psr\Container\ContainerInterface as Container;

/**
 * Required interface for containers that internally use Wire to prevent dependency cycles.
 *
 * When Wire is provided with a container instance for entry lookups, circular dependencies
 * can occur if the container uses Wire for autowiring and the entry is a class name
 * rather than an instantiated object.
 *
 * The `definition` method must return the raw entry value without attempting instantiation.
 */
interface WireContainer extends Container
{
	public function definition(string $id): mixed;
}
