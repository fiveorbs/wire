---
title: PSR-11 Containers
---
The Role of PSR-11 Containers
=================

Autowiring works if all constructor parameters in the entire tree of a class's
dependencies are objects of classes that don't expect literals such as strings,
arrays, or numbers as constructor arguments. Dependencies that expect objects
that are instances of abstract types, such as interfaces or abstract classes,
are also not supported.

These are obviously huge limitations. But there is a solution: The creator and
all resolvers can be initialized with a PSR-11 compatible container. It is used
internally to resolve arguments that cannot be determined otherwise. Entries in
the container have priority. This means: additional autowiring down the
dependency tree will only be used if the type of a parameter does not exist as
an entry in the container.

!!! warn "Do not refuse to use the container" 
    Only in the simplest cases is a container not necessary. If only one of
    your dependencies requires a literal argument, you are doomed. So it is
    a good idea to always combine ***Wire*** with a container implementation.

## The Problem

In the following example, the resolver would not be able to autowire the `Value`
class because an argument to its constructor parameter `$value` that is of type
`string` cannot be determined: 

```
--8<-- "container-problem.php:7"
```

## Solving the problem

By adding the problematic parameter type as an entry to a PSR-11 compatible
container implementation and initializing the creator with it, the problem no
longer exists. As shown in the code block below:

```
--8<-- "container-example.php:7"
```

### Abstract types

If there are abstract types like interfaces or abstract classes expected in the
constructor of a class or its dependencies you also have to work with
a container:

```
--8<-- "container-abstract-example.php:7"
```

## Resolvers and containers

Since the resolvers are the guts and the bones of the creator they work the
same way:

```
--8<-- "resolver-callable-container.php:7"
```

!!! warn "Note" 
    If don't want to use the `Wire` factory to create a resolver you
    have to pass the container via the `Creator` that is needed for all
    resolver constructors.  
    More information:
    [Creating resolvers without factory](resolvers.md/#creating-the-resolvers-without-the-wire-factory)
