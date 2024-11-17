FiveOrbs Wire
==========

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/c68a57b831854a7f9aa8c55461576feb)](https://app.codacy.com/gh/fiveorbs/wire/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/c68a57b831854a7f9aa8c55461576feb)](https://app.codacy.com/gh/fiveorbs/wire/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)
[![Psalm level](https://shepherd.dev/github/fiveorbs/wire/level.svg?)](https://shepherd.dev/github/fiveorbs/wire)
[![Psalm coverage](https://shepherd.dev/github/fiveorbs/wire/coverage.svg?)](https://shepherd.dev/github/fiveorbs/wire)


> [!WARNING]  
> This library is under active development, so some of the features
> listed and parts of the documentation may be still experimental, subject to
> change, or missing.

***Wire*** provides an autowiring object creator that utilizes PHP's reflection
capabilities to automatically resolve constructor arguments recursively. It
additionally comes with classes that assist in resolving arguments of callables
such as functions, methods, closures or class constructors. It can be combined
with a PSR-11 dependency injection container.

**Wire** is a PHP dependency injection tool that automatically constructs
objects by resolving their dependencies. Using PHP's reflection API, **Wire**
recursively analyzes and fulfills constructor arguments without manual
configuration. It additionally includes utilities for resolving dependencies in
various callable types—including functions, methods, closures, and class
constructors. **Wire* seamlessly integrates with PSR-11 compliant dependency
injection containers.

Documentation can be found on the website: [fiveorbs.dev/wire](https://fiveorbs.dev/wire/)

Installation
------------

    composer require fiveorbs/wire

Basic usage
-----------

```php
use FiveOrbs\Wire\Wire;

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

FiveOrbs Wire is released under the MIT [license](LICENSE.md).

Copyright © 2023-2024 ebene fünf GmbH. All rights reserved.
