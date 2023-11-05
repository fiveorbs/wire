<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class Value
{
    public string $str = 'value property';
}

class Model
{
    public function __construct(public string $str, public Value $value)
    {
    }
}

$resolver = Wire::constructorResolver();
$args = $resolver->resolve(
    Model::class,
    ['str' => 'predefined string']
);
$model = new Model(...$args);

assert($model->value->str === 'value property');
assert($model->str === 'predefined string');
