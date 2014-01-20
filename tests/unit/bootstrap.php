<?php
define('CONFIG_DIR', realpath(__DIR__ . '/config'));
$loader = require __DIR__ . '/../../vendor/autoload.php';
$loader->add('Application', __DIR__);
$loader->add('Falcon', __DIR__);
