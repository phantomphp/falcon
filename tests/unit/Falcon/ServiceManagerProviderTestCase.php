<?php

namespace Falcon;

class ServiceManagerProviderTestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $config = require CONFIG_DIR . '/db.php';
        ServiceManager::setConfig($config);
    }

    public function get($instance)
    {
        return ServiceManager::get($instance);
    }

    public function getDb()
    {
        return ServiceManager::getDbRepository();
    }

    public function testNoop()
    {
        return true;
    }
}