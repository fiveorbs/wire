---
title: Introduction
---
Conia Wire
==========

***Wire*** is an autowiring object creator that uses PHP's reflection capabilities to attempt 
to automatically resolve constructor arguments and does so recursively. It is also able to resolve 
arguments of callables such as functions, methods or closures. 

## A simple getting started example:

```php
--8<-- "getting-started.php:7"
```

Here the `Resolver` begins with looking up the types of `Model`'s constructor parameters using reflection. 
If a parameter type is a class it tries to instantiate it by also analyzing its constructor parameters. This works
recursively until all arguments are resolved or it appears an unresolvable parameter.

In the example the first constructor parameter is of type `Value`. `Value` has no constructor parameters and
can therefore safely instantiated. This instance is then passed as argument to the constructor of `Model`.

## More information

* To support resolving of generally unresolvable constructor or callable argument types like literals 
  (`string`, `int`, ect.), ***Wire*** can be combined with a [PSR-11](https://www.php-fig.org/psr/psr-11/) 
  compatible containers implementation. Most of the time this is necessary. 
  See [PSR-11 Containers](container.md).
* If an object needs additional method calls after instatiation to be properly initialized use can use
  the [`Call`](call-attribute.md) class attribute.
