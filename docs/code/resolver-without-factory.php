<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use FiveOrbs\Wire\CallableResolver;
use FiveOrbs\Wire\ConstructorResolver;
use FiveOrbs\Wire\Creator;
// A PSR-11 container implementation like
// https://fiveorbs.dev/registry or https://php-di.org
use FiveOrbs\Wire\Tests\Fixtures\Container;

$container = new Container();
$creator = new Creator($container);
$callableresolver = new CallableResolver($creator);
$constructorResolver = new ConstructorResolver($creator);

// Or without container
$creator = new Creator();
$callableresolver = new CallableResolver($creator);
$constructorResolver = new ConstructorResolver($creator);
