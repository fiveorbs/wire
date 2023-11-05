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
    public string $str;

    public static function create(string $str, Value $value)
    {
        $model = new self();
        $model->value = $value;
        $model->str = $str;

        return $model;
    }
}

$resolver = Wire::callableResolver();
$args = $resolver->resolve(
    [Model::class, 'create'],
    ['str' => 'predefined string']
);
$model = Model::create(...$args);

assert($model->value->str === 'value property');
assert($model->str === 'predefined string');
