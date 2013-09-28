<?php

namespace Falcon;

use Falcon\Repository\DbRepository;
use Falcon\Repository\RepositoryAwareInterface;

use Zend\Db\Adapter\Adapter;

class ServiceManager
{

    private static $config;

    public static function get($classname, $constructorArgs = array())
    {
        $classname = 'Falcon\\' . $classname;
        $instance = new $classname();

        if ($instance instanceof RepositoryAwareInterface) {
            $instance->setRepository(self::getDbRepository());
        }

        return $instance;
    }

    public static function setConfig($config)
    {
        self::$config = $config;
    }

    public static function getDbRepository()
    {
        if (empty(self::$config)) {
            throw new \RuntimeException('Config must be set before calling method: ' . __METHOD__);
        }
        $adapter = new Adapter(self::$config['db']);
        return new DbRepository($adapter);
    }
}