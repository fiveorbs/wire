Conia Wire
==========

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/coniadev/wire.svg)](https://scrutinizer-ci.com/g/coniadev/wire/code-structure)
[![Psalm coverage](https://shepherd.dev/github/coniadev/wire/coverage.svg?)](https://shepherd.dev/github/coniadev/wire)
[![Psalm level](https://shepherd.dev/github/coniadev/wire/level.svg?)](https://conia.dev/wire)
[![Quality Score](https://img.shields.io/scrutinizer/g/coniadev/wire.svg)](https://scrutinizer-ci.com/g/coniadev/wire)

***Wire*** provides an autowiring object creator that utilizes PHP's reflection
capabilities to automatically resolve constructor arguments recursively. It
additionally comes with classes that assist in resolving arguments of callables
such as functions, methods, closures or class constructors. It can be combined
with a PSR-11 dependency injection container.

Documentation can be found on the website: [conia.dev/wire](https://conia.dev/wire/)

## Installation

    composer require conia/wire

## Basic usage

```php
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
    public function __construct(protected Value $value) {}

    public function value(): string
    {
        return $this->value->get();
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class);

assert($model instanceof Model);
assert($model->value() === 'Autowired Value');
```

## License

Conia Wire is released under the MIT [license](LICENSE.md).

Copyright © 2023 ebene fünf GmbH. All rights reserved.
