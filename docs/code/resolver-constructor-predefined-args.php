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
    public function __construct(
        public string $arg,
        public string $type,
        public Value $value
    ) {
    }
}

$resolver = Wire::constructorResolver();
$args = $resolver->resolve(
    Model::class,
    predefinedArgs: ['arg' => 'predefined argument'],
    predefinedTypes: ['string' => 'predefined type']
);
$model = new Model(...$args);

assert($model->value->str === 'value property');
assert($model->arg === 'predefined argument');
assert($model->type === 'predefined type');
