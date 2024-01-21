<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Call;
use Conia\Wire\Wire;

class Value
{
    public function get(): string
    {
        return 'Autowired Value';
    }
}

#[Call('setValue')]
class Model
{
    public ?Value $value = null;

    public function setValue(Value $value): void
    {
        $this->value = $value;
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class);

assert($model->value->get() === 'Autowired Value');
