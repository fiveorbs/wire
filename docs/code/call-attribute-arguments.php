<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Call;
use Conia\Wire\Wire;

#[Call('setString', str: 'Coming from attribute')]
class Model
{
    public string $str = '';

    public function setString(string $str): void
    {
        $this->str = $str;
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class);

assert($model->str === 'Coming from attribute');
