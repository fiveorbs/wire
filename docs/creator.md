---
title: The Creator
---
The Object Creator
==================

***Wire***'s object `Creator` class helps you create instances of classes for
which you don't have their constructor arguments at hand. It attempts to obtain
or gather all the required values by analyzing the types of the constructor
parameters of the class. 

## Basic usage of the object creator

```
--8<-- "creator-basic-usage.php:7"
```

Behind the scenes, Wire will create both the `Value` and the `Model` objects.
Since a `Value` object is required to initialize `Model` it is created
beforehand and then passed to the constructor of `Model`. `Value` does not have
constructor parameters and can therefore be instantiated safely.

Technically, the creator uses reflection to determine the types of `Model`'s
constructor parameters. If a parameter type is a class the creator will try to
instantiate it by analyzing its constructor parameters as well. This process
works recursively, continuously resolving arguments until they are all
resolved, or until it encounters an unresolvable parameter.

!!! info "PSR-11 Containers"
    When combined with a PSR-11 compatible dependency injection container, the
    creator can be assisted in resolving parameters that would otherwise be
    unresolvable.  
    See [PSR-11 Containers](container.md).

## Passing already known arguments

If you already have some or all of the arguments available to you, you can pass
them to the create method by using an associative array like this:

```
--8<-- "creator-arguments.php:7"
```

## Parameters with default values

If a parameter has a default value and is otherwise unresolvable, the default
value is used:

```
--8<-- "creator-default-values.php:7"
```

## Creating the creator without the `Wire` factory

Internally the `Wire` factory initializes the creator like shown here:

```
--8<-- "creator-without-factory.php:7"
```
