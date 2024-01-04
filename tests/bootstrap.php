<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set("display_errors", 1);

if(file_exists(__DIR__ . '/../vendor/autoload.php')) {
    // dependencies were installed via composer - this is the main project
    $classLoader = require __DIR__ . '/../vendor/autoload.php';
} elseif(file_exists(__DIR__ . '/../../../../../autoload.php')) {
    // installed as a dependency in `vendor`
    $classLoader = require __DIR__ . '/../../../../../autoload.php';
} else {
    throw new Exception('Can\'t find autoload.php. Did you install dependencies via composer?');
}
