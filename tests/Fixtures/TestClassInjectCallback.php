<?php

declare(strict_types=1);

namespace FiveOrbs\Wire\Tests\Fixtures;

use FiveOrbs\Wire\Inject;
use FiveOrbs\Wire\Type;

class TestClassInjectCallback
{
	public readonly string $callback;

	public function __construct(
		#[Inject('callback', Type::Callback, id: 'injected id')]
		string $callback,
	) {
		$this->callback = $callback;
	}

	public static function create(
		#[Inject('create callback', Type::Callback, id: 'injected id')]
		string $callback,
	): self {
		return new self($callback);
	}
}
