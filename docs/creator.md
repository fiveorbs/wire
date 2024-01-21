---
title: The Creator
---
The Object Creator
==================

***Wire***'s object `Creator` class helps you create instances of classes for
which you don't have their constructor arguments at hand. It attempts to obtain
or gather all the required values by analyzing the types of the constructor
parameters of the class. 

Basic usage of the object creator
---------------------------------

```
--8<-- "creator-basic-usage.php:9"
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

Factory methods
---------------

If a class uses a static factory method to create an instance, you can pass the 
name of the method to `Creator::create`:

```
--8<-- "creator-factory-method.php:9"
```

Parameters with default values
------------------------------

If a parameter has a default value and is otherwise unresolvable, the default
value is used:

```
--8<-- "creator-default-values.php:9"
```

Assist the creator with arguments that are already available
------------------------------------------------------------

### Predefined arguments

If you already have some or all of the arguments available to you, you can pass
them to the create method by using an associative array. Predefined arguments
are matched by name. When the name of the parameter to be resolved is the same
as a key in the associative array, the value of that key is passed as the
argument.

```
--8<-- "creator-predefined-arguments.php:9"
```

### Predefined types

These are very similar to predefined arguments. But instead of using
a parameter's name to find a match in the associative array, it uses its type.
Additionally, they are also used deeper down the object tree:

```
--8<-- "creator-predefined-types.php:9"
```

You can also combine predefined types with the [`Inject` attribute](inject-attribute.md):

```
--8<-- "creator-predefined-inject.php:9"
```

Creating the creator without the `Wire` factory
-----------------------------------------------

Internally the `Wire` factory initializes the creator like shown here:

```
--8<-- "creator-without-factory.php:9"
```
