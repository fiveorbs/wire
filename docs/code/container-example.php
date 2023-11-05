<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Tests\Fixtures\Container;
use Conia\Wire\Wire;

class Value
{
    public function __construct(protected string $value)
    {
    }

    public function get(): string
    {
        return $this->value;
    }
}

class Model
{
    public function __construct(protected Value $valueObj)
    {
    }

    public function get(): string
    {
        return $this->valueObj->get();
    }
}

$container = new Container();
$container->add(Value::class, new Value('Model value'));

$creator = Wire::creator($container);
$model = $creator->create(Model::class);

assert($model->get() === 'Model value');
