<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Zend\Cache\StorageFactory' => function() {
                return Zend\Cache\StorageFactory::factory(
                    array(
                        'adapter' => array(
                            'name' => 'filesystem',
                            'options' => array(
                                'ttl' => 86000,
                                'cacheDir' => '/tmp/',
                                'dirPermission' => 0755,
                                'filePermission' => 0666,
                                'namespaceSeparator' => '-falcon-'
                            ),
                        ),
                        'plugins' => array('serializer'),
                    )
                );
            }
        ),
        'aliases' => array(
            'cache' => 'Zend\Cache\StorageFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
         ),
     ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Base' => 'Application\Controller\BaseController',
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Auth' => 'Application\Controller\AuthController',
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
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
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
