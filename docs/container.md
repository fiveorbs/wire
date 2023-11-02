---
title: PSR-11 Containers
---
PSR-11 Containers
=================

The `Resolver` class can be initialized with a PSR-11 container, it is used internally 
to resolve arguments that cannot be determined through Reflection. Entries in the container have 
priority. That means reflection is only used if the type of an argument does not exists as entry
in the container.

In the following example the resolver would not be able to autowire the `Arg` class as it has constructor
parameter of type `string`. 

```
use Conia\Wire\Resolver;

class Value { 
    public function __construct(protected string $value) {}
}

$resolver = new Resolver();

// ERROR: throws `WireException`
$resolve->creaete(Value::class);
```

This problem can be solved by initializing the relsolver with a PSR-11 compatible container
implementation, as shown in this slightly more complicated example:


```
use Conia\Wire\Resolver;
use Your\Psr11\Container;

class Value { 
    public function __construct(protected string $value) {}
    public function get(): string { return $this->value; }
}

class Object { 
    public function __construct(protected Value $valueObj) {}
    public function print(): void { echo $this->valueObj->get(); }
}

$container = new Container();
$container->add(Value::class, function() {
    return new Value('test');
});

$resolver = new Resolver($container);
$object = $resolver->create(Object::class);

// Prints 'test'
$object->print();
```
