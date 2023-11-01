<?php

declare(strict_types=1);

namespace Conia\Resolver\Tests\Fixtures;

use Psr\Container\ContainerInterface;

class TestContainer implements ContainerInterface
{
    protected array $entries = [];

    public function add(string $id, mixed $entry): void
    {
        $this->entries[$id] = $entry;
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function get(string $id): mixed
    {
        return $this->entries[$id];
    }
}
