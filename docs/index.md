---
title: Introduction
---
Conia Wire
==========

***Wire*** provides an autowiring object creator that utilizes PHP's reflection
capabilities to automatically resolve constructor arguments recursively. It
additionally comes with classes that assist in resolving arguments of callables
such as functions, methods, closures or class constructors. It can be combined
with a PSR-11 dependency injection container.

## Installation

    composer require conia/wire

## How to create objects

To create an object without knowing its classes constructor arguments, simply
create a [`Creator`](creator.md) instance and pass the fully qualified class
name to its resolver method:

```
--8<-- "creator-basic-usage.php:7:8"
--8<-- "creator-basic-usage.php:29:32"
```

 For a complete introduction and fully working examples, see
[The Creator](creator.md)

## How to resolve arguments

To get a list of instantiated arguments for a callable or a constructor you can
use one of the resolvers. Here you can see simplified example on how to use the
[`CallableResolver`](resolvers.md):

```
--8<-- "resolver-callable-function.php:14:22"
```

More information on callable and constructor argument resolvers and fully
functioning examples can be found here: [Argument resolvers](resolvers.md)

## Other features

* To be able to resolve generally unresolvable constructor or callable argument
  types like literals (`string`, `int`, etc.), interfaces, or abstract classes,
  ***Wire***'s classes can be combined with
  a [PSR-11](https://www.php-fig.org/psr/psr-11/) compatible container
  implementation. See [PSR-11 Containers](container.md).
* If an object needs additional method calls after instantiation to be properly
  initialized you can use the [`Call`](call-attribute.md) class attribute.
