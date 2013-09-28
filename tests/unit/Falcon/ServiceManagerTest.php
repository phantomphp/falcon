<?php

namespace Falcon;

use Falcon\ServiceManagerProviderTestCase;

class ServiceManagerTest extends ServiceManagerProviderTestCase
{

    public function testUserRepo()
    {
        $instance = ServiceManager::get('User\UserRepo');
        $this->assertInstanceOf('Falcon\User\UserRepo', $instance);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetDbRepositoryThrowsExceptionWithoutConfig()
    {
        ServiceManager::setConfig(NULL);
        $db = ServiceManager::getDbRepository();
    }

    public function testGetDbRepository()
    {
        $db = ServiceManager::getDbRepository();
        $this->assertInstanceOf('Falcon\Repository\DbRepository', $db);
    }
}