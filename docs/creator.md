---
title: The Creator
---
The Object Creator
==================

***Wire***'s object `Creator` class helps you create instances of classes for
which you don't have their constructor arguments at hand. It attempts to
instantiate or collect all necessary values by analyzing the types of the
class's constructor parameters. 

## Basic usage of the object creator

```
--8<-- "creator-basic-usage.php:7"
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

!!! info "PSR-11 Containers"
    Combined with a PSR-11 compatible dependency injection container the
    creator can be assisted to resolve otherwise unresolvable paramters. See
    [PSR-11 Containers](container.md).

## Passing already known arguments

If you already have some or all of the arguments available to you, you can pass
them to the create method by using an associative array like this:

```
--8<-- "creator-arguments.php:7"
```

## Parameters with default values

If a parameter has a default value and is otherwise unresolvable, the default value is used:

```
--8<-- "creator-default-values.php:7"
```
