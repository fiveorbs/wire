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
        protected string $str,
        protected Value $value
    ) {
    }

    public function value(): string
    {
        return $this->value->get() . ' ' . $this->str;
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class, predefinedArgs: ['str' => 'and str']);

assert($model instanceof Model);
assert($model->value() === 'Autowired Value and str');
