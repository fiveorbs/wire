---
title: Introduction
---
Conia Wire
==========

**Conia Wire** is an autowiring object generator that uses PHP's reflection capabilities to attempt to automatically 
resolve constructor arguments. It is also able to resolve arguments of callables such as functions, methods or closures. 

## A simple getting started example:

```
use Conia\Wire\Resolver;

class Value { 
    public function __construct() {}
    public function get(): string { return 'test' }
}

class Object { 
    public function __construct(protected Value $valueObj) {}
    public function print(): void { echo $this->valueObj->get(); }
}

$resolver = new Resolver();
$object = $resolver->create(Object::class);

// Prints 'test'
$object->print();
```

Here the `Resolver` begins with looking up the types of `Object`'s constructor parameters using reflection. 
If a parameter type is a class it tries to instantiate it by also analyzing its constructor parameters. This works
recursively until all arguments are resolved or it appears an unresolvable parameter.

In the example the first constructor parameter is of type `Value`. `Value` has no constructor parameters and
can therefore safely instantiated. This instance is then passed as argument to the constructor of `Object`.

## More information

* To support resolving generally unresolvable constructor or callable argument types like literals 
  (`string`, `int`, ect.), it can be combined with a [PSR-11](https://www.php-fig.org/psr/psr-11/) 
  compatible containers implementation. Most of the time this is necessary. 
  See [PSR-11 Containers](container.md).
