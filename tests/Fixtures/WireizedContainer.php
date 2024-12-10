<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

use FiveOrbs\Wire\WireContainer;

class WireizedContainer extends Container implements WireContainer
{
	public function definition(string $id): mixed
	{
		return $this->entries[$id];
	}
}
