<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Creator;
use Conia\Wire\Tests\Fixtures\Container;

$container = new Container();
$creator = new Creator($container);
