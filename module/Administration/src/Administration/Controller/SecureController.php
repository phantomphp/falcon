<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\Partial;
use Zend\Mvc\MvcEvent;


class SecureController extends AbstractActionController
{
    public function onDispatch(MvcEvent $e)
    {
        parent::onDispatch($e);
        if (!$this->identity()) {
            $this->flashMessenger()->addInfoMessage('You need to login first.');
            $this->redirect()->toUrl('login');
        } elseif (!$this->identity()->isAdmin()) {
            $this->redirect()->toUrl('/');
        }
    }

}
