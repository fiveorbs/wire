---
title: PSR-11 Containers
---
The Role of PSR-11 Containers
=================

Autowiring works if all constructor parameters in the entire tree of a class's
dependencies are objects of classes that don't expect literals like strings,
arrays or numbers as constructor arguments. But there is a workaround: The
constructor and all resolvers can be initialized with a PSR-11 container . It
is used internally to resolve arguments that can not otherwise be determined by
reflection. Entries in the container take precedence. This means that
reflection is only used if the type of an argument does not exist as an entry
in the container.

## The Problem

In the following example, the resolver would not be able to autowire the `Value`
class because an argument to its constructor parameter `$value` that is of type
`string` can not be determined: 

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
