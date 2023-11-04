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
    public function __construct(protected Value $valueObj)
    {
    }

    public function value(): string
    {
        return $this->valueObj->get();
    }
}

$creator = Wire::creator();
$object = $creator->create(Model::class);

assert($object->value() === 'Autowired Value');
