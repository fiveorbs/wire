<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\CallableResolver;
use Conia\Wire\ConstructorResolver;
use Conia\Wire\Creator;
// A PSR-11 container implementation like
// https://conia.dev/registry or https://php-di.org
use Conia\Wire\Tests\Fixtures\Container;

$container = new Container();
$creator = new Creator($container);
$callableresolver = new CallableResolver($creator);
$constructorResolver = new ConstructorResolver($creator);

// Or without container
$creator = new Creator();
$callableresolver = new CallableResolver($creator);
$constructorResolver = new ConstructorResolver($creator);
