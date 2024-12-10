# The Role of PSR-11 Containers

Autowiring is effective when all the parameters in the constructor of a class
and its dependencies consist of objects of classes that do not require literals
like strings, arrays, or numbers as constructor arguments. Dependencies that
require objects to be instances of abstract types, such as interfaces or
abstract classes, are not supported either.

These are obviously huge limitations. But there is a solution: The creator and
all resolvers can be initialized with a container that is compatible with
PSR-11. It is used internally to resolve arguments that cannot be determined
otherwise. Entries in the container are prioritized. This means that autowiring
will only be used to inject dependencies if the type of a parameter is not
already registered in the container.

!!! warn "Do not refuse to use the container"

    Only in the simplest cases is a container not necessary. If only one of
    your dependencies requires a literal argument, you are doomed. Most of the
    time it is a good idea to combine ***Wire*** with a container
    implementation.

!!! warn "Be warned about the simplified examples"

    In the examples, we utilize a very basic container mock, which is also used
    in ***Wire***'s test suite. More often than not, in the following code
    snippets, we store initialized objects in the container mock. We do this
    only to be able to show easy to understand code.  

    Of course, you should utilize a fully fledged container implementation and
    leverage its functionality to retrieve values that cannot be resolved by
    ***Wire***.

## The Problem

In the following example, the resolver cannot autowire the `Value` class
because it cannot determine the value for the constructor parameter `$value`,
which is of type `string`:

```php
--8<-- "container-problem.php:7"
```

## Solving the problem

By adding the parameter type that was causing the issue to a container
implementation that is compatible with PSR-11, and then initializing the
creator with that container, the problem no longer exists. As shown in the
code block below:

```php
--8<-- "container-example.php:7"
```

### Abstract types

If there are abstract types like interfaces or abstract classes expected in the
constructor of a class or its dependencies you will also need to work with
a container:

```php
--8<-- "container-abstract-example.php:7"
```

## Resolvers and containers

Since the resolvers are the guts and the bones of the creator they work the
same way:

```php
--8<-- "resolver-callable-container.php:7"
```

!!! warn "Note"

    If don't want to use the `Wire` factory to create a resolver you have to
    pass the container via the `Creator` that is needed for all resolver
    constructors.  

    More information:
    [Creating the creator without factory](creator.md/#creating-the-creator-without-the-wire-factory) and
    [Creating resolvers without factory](resolvers.md/#creating-the-resolvers-without-the-wire-factory)
