<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Tests\Fixtures\Container;
use Conia\Wire\Wire;

interface ValueInterface
{
    public function get(): string;
}

class Value implements ValueInterface
{
    public function get(): string
    {
        return 'From Value';
    }
}

class Model
{
    public function __construct(public ValueInterface $value)
    {
    }
}

$container = new Container();
$container->add(ValueInterface::class, new Value());

$creator = Wire::creator($container);
$model = $creator->create(Model::class);

assert($model->value->get() === 'From Value');
