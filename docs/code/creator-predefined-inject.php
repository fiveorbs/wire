<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Inject;
use Conia\Wire\Wire;

class Value
{
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
    #[Inject(another: 'use-this-id')]
    public function __construct(
        public readonly Value $value,
        public readonly AnotherValue $another,
    ) {
    }
}

$creator = Wire::creator();
$model = $creator->create(
    Model::class,
    predefinedTypes: ['use-this-id' => new AnotherValue('predefined value')]
);

assert($model instanceof Model);
assert($model->value->get() === 'Autowired Value');
assert($model->another->str === 'predefined value');
