<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class DeepValue
{
    public function __construct(public readonly string $value)
    {
    }
}

class Value
{
    public function __construct(public readonly DeepValue $deep)
    {
    }

    public function get(): string
    {
        return 'Autowired Value';
    }
}

class AnotherValue
{
    public function __construct(public readonly string $str)
    {
    }
}

class Model
{
    public function __construct(
        public readonly Value $value,
        public readonly AnotherValue $another,
    ) {
    }
}

$creator = Wire::creator();
$model = $creator->create(
    Model::class,
    predefinedTypes: [
        AnotherValue::class => new AnotherValue('predefined value'),
        DeepValue::class => new DeepValue('deep value'),
    ]
);

assert($model instanceof Model);
assert($model->value->get() === 'Autowired Value');
assert($model->value->deep->value === 'deep value');
assert($model->another->str === 'predefined value');
