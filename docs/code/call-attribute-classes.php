<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

class Value
{
    public function get(): string
    {
        return 'Value';
    }
}

class Model
{
    public ?Value $value = null;

    public function setValue(Value $value): void
    {
        $this->value = $value;
    }
}
