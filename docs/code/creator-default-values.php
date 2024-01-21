<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class Model
{
    public function __construct(public string $str = 'default value')
    {
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class);

assert($model instanceof Model);
assert($model->str === 'default value');
