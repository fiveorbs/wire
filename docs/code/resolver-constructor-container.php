<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Tests\Fixtures\Container;
use Conia\Wire\Wire;

class Value
{
    public function __construct(protected string $str)
    {
    }

    public function get(): string
    {
        return $this->str;
    }
}

class Model
{
    public function __construct(protected Value $value)
    {
    }

    public function get(): string
    {
        return $this->value->get();
    }
}

$container = new Container();
$container->add(Value::class, new Value('Model value'));

$resolver = Wire::constructorResolver($container);
$args = $resolver->resolve(Model::class);

assert((new Model(...$args))->get() === 'Model value');
