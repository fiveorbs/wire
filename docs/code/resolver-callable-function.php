<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class Value
{
    public string $str = 'value property';
}

function readValue(Value $value): string
{
    return $value->str;
}

$resolver = Wire::callableResolver();
$args = $resolver->resolve('readValue');

readValue(...$args);

assert(readValue(...$args) === 'value property');
