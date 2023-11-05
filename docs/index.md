---
title: Introduction
---
Conia Wire
==========

***Wire*** provides an autowiring object creator that utilizes PHP's reflection
capabilities to automatically resolve constructor arguments recursively. It
also comes with classes that assist in resolving arguments of callables such as
functions, methods or closures. 

## Installation

    composer require conia/wire

## Other features

* To be able to resolve generally unresolvable constructor or callable argument
  types like literals (`string`, `int`, etc.), interfaces, or abstract classes,
  ***Wire***'s classes can be combined with
  a [PSR-11](https://www.php-fig.org/psr/psr-11/) compatible container
  implementation. See [PSR-11 Containers](container.md).
* If an object needs additional method calls after instatiation to be properly
  initialized you can use the [`Call`](call-attribute.md) class attribute.
* ***Wire*** additionally provides argument resolvers for callables and
  constructors. Simply pass a callable or a class to the appropriate resolver
  and get a list of instantiated arguments for it in return. See [Argument
  resolvers](resolvers.md)

## Basic usage of the object creator

```
--8<-- "basic-usage-creator.php:7"
```

Behind the scenes, Wire will create both the `Value` and the `Model` objects.
Since a `Value` object is necessary to initialize `Model` it is created first and
then passed to the constructor of `Model`. `Value` does not have constructor
parameters and can therefore be instantiated safely.

Technically, the creator uses reflection to look up the types of `Model`'s
constructor parameters. If a parameter type is a class the creator attempts to
instantiate it by analyzing its constructor parameters as well. This works
recursively until all arguments are resolved or it encounters an unresolvable
parameter.

Combined with a PSR-11 compatible dependency injection container the creator
can be guided to resolve otherwise unresolvable paramters. See [PSR-11 Containers](container.md).
