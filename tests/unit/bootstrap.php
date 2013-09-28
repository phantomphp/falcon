<?php
define('CONFIG_DIR', realpath(__DIR__ . '/../../config'));
require __DIR__ . '/../../vendor/autoload.php';
$paths = array(
    get_include_path(),
    __DIR__

);
set_include_path(join(PATH_SEPARATOR, $paths));

function tester_autoload($classname)
{
    $file = str_replace('\\', '/', $classname) . '.php';
    require_once $file;
}

spl_autoload_register('tester_autoload');