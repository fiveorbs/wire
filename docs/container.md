---
title: PSR-11 Containers
---
PSR-11 Containers
=================

The creator and all resolvers (through the creator) can be initialized with
a PSR-11 container, it is used internally to resolve arguments that cannot be
determined through Reflection. Entries in the container have priority. That
means reflection is only used if the type of an argument does not exists as
entry in the container.

## The Problem

In the following example the resolver would not be able to autowire the `Value`
class as its constructor parameter `$value` is of type `string`. 

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
