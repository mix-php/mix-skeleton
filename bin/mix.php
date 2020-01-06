<?php

// Autoload
require __DIR__ . '/../vendor/autoload.php';

// Environment
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

// Config
$config = new Noodlehaus\Config(__DIR__ . '/../config');

// Run application
(new Mix\Console\Application(require __DIR__ . '/../manifest.php'))->run();
