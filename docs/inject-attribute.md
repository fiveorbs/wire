---
title: The Inject Attribute
---
The Inject Attribute
==================

By annotating callables or constructors with an `Inject` attribute, you can
tell the resolvers and, consequently, the creator how to obtain arguments that
cannot be resolved otherwise  or to apply arguments that would not be used by
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
by annotating it with an `Inject` attribute. If the function is not annotated,
the resolver would create an object of the base class `Model` because the type of
the parameter `$model` is `Model`.

## How to use

Simply add the `Inject` attribute to the callable or constructor that you want
to control. Then, you pass a named argument with the same name as the parameter for
which you want to provide an argument. In the example, the parameter name in
question is `$model`. So you pass the identically named argument `model` with
the value you want the callable's argument to have, or, if it's not a literal,
with an identifier from which the value is derived ([see below for a detailed
description](#how-injected-argument-values-are-determined)). 

The snippet below shows the relevant part of the example above, highlighting
the identically named parameters:

<code class="annotated"> <span class="hljs-meta">#[Inject</span>(<span
class="hljs-attr"><span class="spot red">model</span></span>: <span
class="hljs-title class_">SubModel</span>::<span class="hljs-variable
language_">class</span>)<span class="hljs-meta">]</span><br> <span
class="hljs-function"><span class="hljs-keyword">function</span> <span
class="hljs-title">alsoExpectsModel</span>(<span class="hljs-params">Model
<span class="hljs-variable">$<span class="spot
red">model</span></span></span>): <span class="hljs-title">Model</span>
</span>{ <span class="dots">...</span> </code>

You may also add multiple arguments, and you have to only add those you want to
control:

<code class="annotated"><span class="hljs-meta">#[Inject</span>(<span
class="hljs-attr"><span class="spot red">another</span></span>: <span
class="hljs-number">13.73</span>, <span class="hljs-attr"><span class="spot
purple">value</span></span>: <span class="hljs-number">13</span>)<span
class="hljs-meta">]</span> <span class="hljs-function"><br><span
class="hljs-keyword">function</span> <span
class="hljs-title">theCallable</span>(<span class="hljs-params"><span
class="hljs-keyword">int</span> <span class="hljs-variable">$<span class="spot
purple">value</span></span>, Model <span class="hljs-variable">$model</span>,
<span class="hljs-keyword">float</span> <span class="hljs-variable">$<span
class="spot red">another</span></span></span>): <span
class="hljs-title">Model</span> </span>{ </code>

## How injected argument values are determined

The resolvers behave differently depending on the type of value that you want
to be injected. Especially when dealing with strings and arrays, they are
treated in a specific manner.

!!! warn "Warning" 
    The resolver does not check if a value which was obtained with the help of
    an `Inject` attribute matches the parameters type of the callable it
    should be applied to, so handle with care.

### Strings

If the value is a string, like in the following example:

```php
#[Inject(param: 'container.id')]
// or
#[Inject(param: SubModel::class)]
```

the resolver uses the following rules.

1. If a container is available, see if it has an entry with and id matching te
   value of the string. If so, return it, if not continue with step 2.
2. If the string is the full qualified name of an existing class, try to create
   it using the creator and return it. If not, continue with step 3.
3. Return the string as-is.

### Arrays

If the value is a array, as shown here:

```php
#[Inject(param: ['number' => 13, 'str' => 'value'])]
```

the resolver uses the following rules.

1. If the array follows a specific format, it is examined and a corresponding
   value is generated based on its contents. If it does not have that specific
   format, continue with step 2.  For details see: [Don't follow the
   rules](#dont-follow-the-rules)
2. Return the array as-is.

### The literal rest

All other types, like numbers, booleans or null values are passed to the
callable or are returned by the resolver as they are, i. e. unchanged as
literals.

## Don't follow the rules

If you want to bypass the string rules or be explicit about the values you
inject, you can use a specific type of array as an argument for the attribute.
Additionally, with that feature, you can have control over how a value is
generated.

The array must be a list (an array is considered a list if its keys consist of
consecutive numbers starting from 0, i. e. PHP's `array_is_list` returns
`true`) and have a count of exactly 2. Additionally the array's second value
must be a value of the enum `Conia\Wire\Type` which controls what is done with
the first value of the array. 

```php
// a valid array
['value', Conia\Wire\Type::Literal]
```

Supported types are:

### `Conia\Wire\Type::Literal`  

Returns the value as is.

``` php
#[Inject(value: ['a string value', Type::Literal])]
public function myCallable(string $value): void 
```

### `Conia\Wire\Type::Entry`  

Uses the value as id to fetch a value from the [container](container.md).

``` php
$container->add('container.entry.id', new Object());

#[Inject(value: ['container.entry.id', Type::Entry])]
public function myCallable(Object $value): void 
``` 

``` php
$container->add(\Your\Interface::class, new Object());

#[Inject(value: [\Your\Interface::class, Type::Entry])]
public function myCallable(\Your\Interface $value): void 
```
### `Conia\Wire\Type::Create`  

Must be a fully qualified class name which the creator attemtps to create.

``` php
#[Inject(value: [SubModel::class, Type::Create])]
public function myCallable(Model $value): void 
``` 

### `Conia\Wire\Type::Env`  

The value is assumed to be the name an environment variable. It attempts to
read the environment variable using PHP's internal function `getenv` and then
returns its value.

``` php
#[Inject(value: ['PATH', Type::Env])]
public function myCallable(string|bool $value): void {
    // $value has now the content of the environment variable PATH
}
``` 
