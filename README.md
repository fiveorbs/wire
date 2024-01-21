Conia Wire
==========

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/e0fec62285124af1bea3a00d64c120df)](https://app.codacy.com/gh/coniadev/wire/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/e0fec62285124af1bea3a00d64c120df)](https://app.codacy.com/gh/coniadev/wire/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)
[![Psalm level](https://shepherd.dev/github/coniadev/wire/level.svg?)](https://conia.dev/wire)
[![Psalm coverage](https://shepherd.dev/github/coniadev/wire/coverage.svg?)](https://shepherd.dev/github/coniadev/wire)

***Wire*** provides an autowiring object creator that utilizes PHP's reflection
capabilities to automatically resolve constructor arguments recursively. It
additionally comes with classes that assist in resolving arguments of callables
such as functions, methods, closures or class constructors. It can be combined
with a PSR-11 dependency injection container.

Documentation can be found on the website: [conia.dev/wire](https://conia.dev/wire/)

Installation
------------

    composer require conia/wire

Basic usage
-----------

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

License
-------

Conia Wire is released under the MIT [license](LICENSE.md).

Copyright © 2023-2024 ebene fünf GmbH. All rights reserved.
