---
title: Argument Resolvers
---
Argument Resolvers
==================

***Wire*** provides two different autowiring argument resolvers, one for
[callables](#resolve-callable-arguments) like functions, closures, etc and
another for [constructors](#resolve-constructor-arguments). These resolvers
are used internally by the creator.

## Resolve callable arguments

To create an associative array of callable arguments, create
a `CallableResolver` instance using the `Wire` factory and pass the callable to
the `resolve` method. You can then pass the arguments to the
callable via the `...` operator, for example.

### Functions

```
--8<-- "resolver-callable-function.php:7:21"
--8<-- "resolver-callable-function.php:24"
```

### Closures

```
--8<-- "resolver-callable-closure.php:7"
```

### Instance methods

```
--8<-- "resolver-callable-instance-method.php:7"
```

### Static methods

```
--8<-- "resolver-callable-static-method.php:7"
```

## Resolve constructor arguments

You simply pass the fully qualified class name to the `resolve` method of the
`ConstructorResolver` instance created by the `Wire` factory:

```
--8<-- "resolver-constructor-basic.php:7"
```
!!! info "Factory methods"
    If you have a class with a factory method you can use the callable resolver as
    shown in the [static methods](#static-methods) example.

## Passing predefined arguments

If you have some of the necessary arguments already at hand, you can pass them
converted to an associative array as second argument to the `resolve` method.
The array's keys must match the names of the parameters.

An example using the callable resolver:

```
--8<-- "resolver-callable-predefined-args.php:7"
```

The constructor resolver works the same way:

```
--8<-- "resolver-constructor-predefined-args.php:21:26"
```

## Using a PSR-11 container

Like the creator, resolvers can be initialized with a container to help with
unresolvable parameter arguments. (See also: [PSR-11
Containers](containers.md)) 

```
--8<-- "resolver-constructor-container.php:7"
```

An example using the callable resolver:

```
--8<-- "resolver-callable-container.php:7"
```


## Creating the resolvers without the `Wire` factory

Internally the `Wire` factory initializes the resolvers with the creator like shown here:

```
--8<-- "resolver-without-factory.php:7"
```
