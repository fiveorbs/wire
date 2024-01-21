---
title: Argument Resolvers
---
Argument Resolvers
==================

***Wire*** provides two different autowiring argument resolvers, one for
[callables](#resolve-callable-arguments) like functions, closures, etc and
another for [constructors](#resolve-constructor-arguments). These resolvers
are used internally by the creator.

Resolve callable arguments
--------------------------

To create an associative array of callable arguments, create
a `CallableResolver` instance using the `Wire` factory and pass the callable to
the `resolve` method. You can then pass the arguments to the
callable via the `...` operator, for example.

### Functions

```
--8<-- "resolver-callable-function.php:9:23"
--8<-- "resolver-callable-function.php:26"
```

### Closures

```
--8<-- "resolver-callable-closure.php:9"
```

### Instance methods

```
--8<-- "resolver-callable-instance-method.php:9"
```

### Static methods

```
--8<-- "resolver-callable-static-method.php:9"
```

Resolve constructor arguments
-----------------------------

You simply pass the fully qualified class name to the `resolve` method of the
`ConstructorResolver` instance created by the `Wire` factory:

```
--8<-- "resolver-constructor-basic.php:9"
```
!!! info "Factory methods"
    If you have a class with a factory method you can use the callable resolver as
    shown in the [static methods](#static-methods) example.

Assist resolvers with arguments that are already available
----------------------------------------------------------

If you have some of the necessary arguments already at hand, you can pass them
converted to an associative array to the `resolve` method's `predefinedArgs`
and/or `predefinedTypes` parameters. The array's keys must match the names or types of
the parameters of the callable to be resolved.  
For more information, especially the difference between
`predefinedArgs` and `predefinedTypes`, see [`Creator`'s section about the same
topic](/creator/#assist-the-creator-with-arguments-that-are-already-available),
which works in a similar way.

An example using the callable resolver:

```
--8<-- "resolver-callable-predefined-args.php:9"
```

The constructor resolver works the same way:

```
--8<-- "resolver-constructor-predefined-args.php:9"
```

Using a PSR-11 container
------------------------

Like the creator, resolvers can be initialized with a container to help with
unresolvable parameter arguments. (See also: [PSR-11
Containers](containers.md)) 

```
--8<-- "resolver-constructor-container.php:9"
```

An example using the callable resolver:

```
--8<-- "resolver-callable-container.php:9"
```


Creating the resolvers without the `Wire` factory
-------------------------------------------------

Internally the `Wire` factory initializes the resolvers with the creator like shown here:

```
--8<-- "resolver-without-factory.php:9"
```
