<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAdministration for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'service_manager' => array(
        'factories' => array(
            //'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
        'aliases' => array(

        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Administration\Controller\Secure' => 'Administration\Controller\SecureController',
            'Administration\Controller\Index' => 'Administration\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'partial/menu'             => __DIR__ . '/../view/partial/menu.phtml',
            'administration/index/index' => __DIR__ . '/../view/administration/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'alert' => 'Application\View\Helper\Alert',
            'partial' => 'Zend\View\Helper\Partial'
        ),
    ),
    'log' => array(
        'log-path' => '/tmp/falcon.log'
    )
);
