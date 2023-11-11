---
title: The Call Attribute
---
The Call Attribute
==================

Occasionally, especially when a dependency is optional, there may be additional
steps required to initialize an object beyond simply invoking the constructor.
When you encounter this situation, you can use the `Call` attribute to annotate
a class and specify which method should be called after the object is created
using the constructor.

## Without annotation

Given the following simplified example:

```php
--8<-- "call-attribute-classes.php:7:30"
```

Without ***Wire***'s help, you need to create a `Model` instance, and then call
the `setValue` method, passing a `Value` object as an argument.

```php
--8<-- "call-attribute-without.php:7"
```

## Annotated with the `Call` attribute

If you create an object using the creator and the class is annotated with one
or more `Call` attributes, autowiring will automatically invoke any method
specified in the attribute(s) as the first argument.


```php
--8<-- "call-attribute-example.php:7"
```

## Multiple method calls

As mentioned before, you can annotate a class with multiple `Call` attributes.
The methods are invoked in the same order in which the attributes are defined.

```php
--8<-- "call-attribute-multiple.php:7"
```

## Provide arguments

If an argument for a method cannot be autowired, such as literal values like
strings or numbers, you can pass them as named arguments to the `Call`
attribute. This serves as a hint for the autowiring mechanism. The names must
match the parameter names of the callable:

```php
--8<-- "call-attribute-arguments.php:7"
```
