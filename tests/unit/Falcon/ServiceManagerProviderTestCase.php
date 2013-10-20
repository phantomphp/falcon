<?php

namespace Falcon;

use Falcon\Repository\RepositoryAwareInterface;

class ServiceManagerProviderTestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $config = require CONFIG_DIR . '/db.php';
        ServiceManager::setConfig($config);
    }

    public function get($instance)
    {
        $class = ServiceManager::get($instance);
        if ($class instanceof RepositoryAwareInterface) {
            $class->setRepository($this->getDb());
        }
        
        return $class;
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