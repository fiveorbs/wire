<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class Value
{
    public string $str = 'value property';
}

$closure =  function (Value $value): string {
    return $value->str;
};
$resolver = Wire::callableResolver();
$args = $resolver->resolve($closure);

assert($closure(...$args) === 'value property');
