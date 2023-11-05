---
title: The Call Attribute
---
The Call Attribute
==================

Occasionally, especially if a dependency is optional, there are more steps
necessary to initialize an object than the constructor invocation. When this is
the case you can annotate a class with the `Call` attribute to define which
method has to be called after the object is instantiated via the constructor.

## Without annotation

Given the following simplified example:

```php
--8<-- "call-attribute-classes.php:7:30"
```

Without autowiring, you must first create a `Model` instance, and then you must
call the `setValue` method and pass a `Value` object to it.

```php
--8<-- "call-attribute-without.php:7"
```

## Annotated with the `Call` attribute

If you instantiate an object using the creator and the class is annotated with
one ore more `Call` attributes, autowiring will automatically call any method
given to the attribute(s) as first argument.

```php
--8<-- "call-attribute-example.php:7"
```

## Multiple method calls

As mentioned before, you can annotate a class with multiple `Call` attributes.
The methods are called in the same order as the attributes are defined.

```php
--8<-- "call-attribute-multiple.php:7"
```

## Provide arguments

If an argument for a method cannot be autowired (for example literal values
like strings or numbers), you can pass them as named arguments as a hint for
the autowiring mechanism to the `Call` attribute. The name must match the
parameter name of the method:

```php
--8<-- "call-attribute-arguments.php:7"
```
