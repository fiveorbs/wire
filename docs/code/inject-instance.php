<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Inject;
use Conia\Wire\Type;

$inject = new Inject('value', Type::Literal, text: 'string', number: 13);

assert($inject->value === 'value');
assert($inject->type === Type::Literal);
assert($inject->meta['text'] === 'string');
assert($inject->meta['number'] === 13);

$inject = new Inject('value', text: 'string', number: 13);

assert($inject->value === 'value');
assert($inject->type === null);
assert($inject->meta['text'] === 'string');
assert($inject->meta['number'] === 13);

$inject = new Inject('value', null, 31, 73, 'text');

assert($inject->value === 'value');
assert($inject->type === null);
assert($inject->meta === [31, 73, 'text']);
