<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Inject;
use Conia\Wire\Wire;

class Model
{
}

class SubModel extends Model
{
}

function expectsModel(Model $model): Model
{
    return $model;
}

#[Inject(model: SubModel::class)]
function alsoExpectsModel(Model $model): Model
{
    return $model;
}

$resolver = Wire::callableResolver();

// `expectsModel` is not annotated. An object of the base class is created
$args = $resolver->resolve('expectsModel');
$result = expectsModel(...$args);
assert($result instanceof Model);

// `alsoExpectsModel` is annotated. An object of the sub class is created
$args = $resolver->resolve('alsoExpectsModel');
$result = alsoExpectsModel(...$args);
assert($result instanceof SubModel);
