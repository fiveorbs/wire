<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace,PSR1.Files.SideEffects.FoundWithSymbols,PSR1.Classes.ClassDeclaration.MultipleClasses

declare(strict_types=1);

require __DIR__ . '/call-attribute-classes.php';

$model = new Model();
$model->setValue(new Value());

assert($model->value->get() === 'Value');
