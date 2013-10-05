<?php

namespace Application\Controller;

use Application\Authentication\DbAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result as AuthResult;
use Zend\View\Model\ViewModel;
use Falcon\ServiceManager;


class AuthController extends AbstractActionController
{
    public function loginAction()
    {
        if ($this->identity()) {
            $this->redirect()->toUrl('/');
        }
        if ($this->getRequest()->isPost()) {
            $adapter = $this->getServiceLocator()->get('DbAuthAdapter');
            $adapter->setUsername($this->getRequest()->getPost('username'));
            $adapter->setPassword($this->getRequest()->getPost('password'));
            $authService = new AuthenticationService(null, $adapter);
            $result = $authService->authenticate();
            if ($result->getCode() != AuthResult::SUCCESS) {
                $this->flashMessenger()->addErrorMessage('Invalid username or password.');
                $this->redirect()->toRoute('login');
            } else {
                //$this->getServiceLocator()->get('log')->info('Logged in: ' . $this->getRequest()->getPost('username'));
                $this->redirect()->toUrl('/');
            }
        }

    }
    
    public function logoutAction()
    {
        $authService  = new AuthenticationService;
        if ($authService->hasIdentity()) {
            //$this->getServiceLocator()->get('log')->info('Logged out: ' . $authService->getIdentity());
        }
        $authService->clearIdentity();
        $this->flashMessenger()->addSuccessMessage('You have logged out.');
        $this->redirect()->toRoute('login');
    }
    
}
