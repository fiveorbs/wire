---
title: The Inject Attribute
---
The Inject Attribute
==================

By annotating function or method parameters with an `Inject` attribute, you can
tell the resolvers and, consequently, the creator how to obtain arguments that
cannot be resolved otherwise or to apply arguments that would not be used by
default. This means that you can use it to override ***Wire***'s default
behavior, for example when you want to choose one of several alternatives or
when there are literal arguments such as strings, numbers or arrays expected.

## Example

Let's assume you have two different functions, each of which requires a `Model`
object as input. But you want to ensure that one of the functions always
receives a `SubModel` instance, which is a subclass of `Model`. The following
example shows how to accomplish that:

```
--8<-- "inject-example.php:7"
```

You can control the behavior of the function (in this case, `alsoExpectsModel`)
by annotating the parameter it with an `Inject` attribute. If the parameter is
not annotated, the resolver would create an object of the base class `Model`
because the type of the parameter `$model` is `Model`.

## How to use

Simply add the `Inject` attribute to a parameter of a callable or constructor that you want
to control. You pass a mandatory argument with
the value you want the callable's argument to have, or, if it's not a literal,
with an identifier from which the value is derived ([see below for a detailed
description](#how-injected-argument-values-are-determined)). 

The snippet below shows the relevant part of the example above:

<code class="annotated"> <span class="hljs-function"><span
class="hljs-keyword">function</span> <span
class="hljs-title">alsoExpectsModel</span>( <br>&nbsp;&nbsp;&nbsp;&nbsp;<span
class="hljs-meta">#[Inject</span>(<span class="hljs-title
class_">SubModel</span>::<span class="hljs-variable
language_">class</span>)<span
class="hljs-meta">]</span><br>&nbsp;&nbsp;&nbsp;&nbsp;<span
class="hljs-params">Model <span
class="hljs-variable">$model</span></span><br>): <span
class="hljs-title">Model</span> </span>{ <span class="dots">...</span> </code>

## How injected argument values are determined

The resolvers behave differently depending on the type of value that you want
to be injected. 

!!! warn "Warning" 
    The resolver does not check if a value which was obtained with the help of
    an `Inject` attribute matches the parameters type of the callable it
    should be applied to, so handle with care.

### Strings

If the value is a string, like in the following example:

```php
#[Inject('container.id')]
// or
#[Inject(SubModel::class)]
```

the resolver uses the following rules.

1. If a container is available, see if it has an entry with and id matching the
   value of the string. If so, return it, if not continue with step 2.
2. If the string is the full qualified name of an existing class, try to create
   it using the creator and return it. If not, continue with step 3.
3. Return the string as-is.

### The literal rest

All other types, like arrays, numbers, booleans or null values are passed to
the callable or are returned by the resolver as they are, i. e. as unchanged
literals.

```php
function withLiteralParams(
     #[Inject(['number' => 13, 'str' => 'value'])]
     array $arrayParam,

     #[Inject(73)]
     int $integerParam,
     
     #[Inject(13.37)]
     float $floatParam,

     #[Inject(true)]
     bool $booleanParam,

     #[Inject(null)]
     ?string $nullableParam,
) { ...
```

## Don't follow the rules

If you want to bypass the string rules or be explicit about the values you
inject, you can specifiy the type of the injected value.
Additionally, with that feature, you can have control over how a value is
generated.

The inject type is passed as second argument to the `Inject` attribute und must
be of the data type `Conia\Wire\Type`:

```php
// a valid array
#[Inject('value', Conia\Wire\Type::Literal)]
```

Supported types are:

### `Conia\Wire\Type::Literal`  

Returns the value as is.

``` php
#[Inject('a string value', Type::Literal)]
public function myCallable(string $value): void 
```

### `Conia\Wire\Type::Entry`  

Uses the value as id to fetch a value from the [container](container.md).

``` php
$container->add('container.entry.id', new Object());

public function myCallable(
    #[Inject('container.entry.id', Type::Entry)]
    Object $value
): void 
``` 

``` php
$container->add(\Your\Interface::class, new Object());

public function myCallable(
    #[Inject(\Your\Interface::class, Type::Entry)]
    \Your\Interface $value
): void 
```
### `Conia\Wire\Type::Create`  

Must be a fully qualified class name which the creator attemtps to create.

``` php
public function myCallable(
     #[Inject(SubModel::class, Type::Create)] 
     Model $value
): void 
``` 

### `Conia\Wire\Type::Env`  

The value is assumed to be the name an environment variable. It attempts to
read the environment variable using PHP's internal function `getenv` and then
returns its value.

``` php

public function myCallable(
    #[Inject('PATH', Type::Env)]
    string|bool $value
): void {
    // $value has now the content of the environment variable PATH
}
``` 
