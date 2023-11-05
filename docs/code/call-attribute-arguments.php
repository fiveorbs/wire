<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Call;
use Conia\Wire\Wire;

#[Call('setValue', value: 'Coming from attribute')]
class Model
{
    public string $value = '';

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class);

assert($model->value === 'Coming from attribute');
