<?php

declare(strict_types=1);

$files = glob(__DIR__ . '/../docs/code/*.php');

foreach ($files as $file) {
    system('php ' . realpath($file), $value);

    if ($value !== 0) {
        exit($value);
    }

    exit(0);
}
