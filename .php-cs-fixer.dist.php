<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()->in([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/docs/code']);
$config = new FiveOrbs\Development\PhpCsFixer\Config();

return $config->setFinder($finder);
