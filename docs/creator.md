---
title: The Creator
---
The Object Creator
==================

***Wire***'s object `Creator`

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
