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

function readValue(Value $value): string
{
    return $value->get();
}

$container = new Container();
$container->add(Value::class, new Value('Model value'));

$resolver = Wire::callableResolver($container);
$args = $resolver->resolve('readValue');

assert(readValue(...$args) === 'Model value');
