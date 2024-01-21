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
    public Value $value;
    public string $arg;
    public string $type;

    public static function create(string $arg, string $type, Value $value)
    {
        $model = new self();
        $model->value = $value;
        $model->arg = $arg;
        $model->type = $type;

        return $model;
    }
}

$resolver = Wire::callableResolver();
$args = $resolver->resolve(
    [Model::class, 'create'],
    predefinedArgs: ['arg' => 'predefined argument'],
    predefinedTypes: ['string' => 'predefined type'],
);
$model = Model::create(...$args);

assert($model->value->str === 'value property');
assert($model->arg === 'predefined argument');
assert($model->type === 'predefined type');
