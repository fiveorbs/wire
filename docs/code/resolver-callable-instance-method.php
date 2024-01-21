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
    public function readValue(Value $value): string
    {
        return $value->str;
    }
}

$model = new Model();
$resolver = Wire::callableResolver();
$args = $resolver->resolve([$model, 'readValue']);

assert($model->readValue(...$args) === 'value property');
