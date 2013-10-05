<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\StaticEventManager;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogStream;
use Zend\Db\Adapter\Adapter;
use Falcon\ServiceManager;

class Module
{

    public function init()
    {
        $events = StaticEventManager::getInstance();
        $events->attach('Application\Controller\BaseController', 'dispatch', array($this, 'preDispatch'), 100);
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sm = $e->getApplication()->getServiceManager();
        $identityPlugin = $sm->get('ControllerPluginManager')->get('identity');
        $authService = new AuthenticationService();
        $identityPlugin->setAuthenticationService($authService);
        $this->preDispatch($e);
		ServiceManager::setConfig($this->getConfig());
    }

    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/router.config.php'
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ZendAuthService' => function ($sm) {
                    return new AuthenticationService;
                },
                'log' => function ($sm) {
                    $log = new Logger();
                    $config = $sm->get('Config');
                    $writer = new LogStream($config['log']['log-path']);
                    $log->addWriter($writer);
                    return $log;
                },
				'DbAuthAdapter' => function($sm) {
					$userRepo = ServiceManager::get('User\UserRepo');
					return new Authentication\DbAuthAdapter($userRepo);
				},
            )
        );
    }

    public function preDispatch(MvcEvent $e)
    {

    }
}
