<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class Value
{
    public function get(): string
    {
        return 'Autowired Value';
    }
}

class Model
{
    public function __construct(
        public readonly Value $value,
        public readonly string $str
    ) {
    }

    public static function createModel(
        Value $value,
        string $str = 'default string'
    ): self {
        return new self($value, $str);
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class, constructor: 'createModel');

assert($model instanceof Model);
assert($model->value->get() === 'Autowired Value');
assert($model->str === 'default string');
