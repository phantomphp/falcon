<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration\Controller;

use Falcon\ServiceManager;
use Falcon\Helper\Helper;

use Zend\View\Model\ViewModel;
use Zend\Http\Headers as HttpHeaders;
use Zend\Http\Header\ContentType as HeaderContentType;
use Zend\Http\Header\ContentLength as HeaderContentLength;

class ProductController extends SecureController
{
    public function indexAction()
    {
        $products = ServiceManager::get('Product\ProductRepo')->findAll();
        return new ViewModel(array(
            'products' => $products
        ));
    }
    
    public function detailAction()
    {
        $id = $this->params('id');
        if (!Helper::validateUUID($id)) {
            $this->flashMessenger()->addErrorMessage('Invalid product id: ' . $id);
            $this->redirect()->toUrl('/administration/product/index');
        }
        $product = ServiceManager::get('Product\ProductRepo')->getProductByUUID($id);
        return new ViewModel(array(
            'attributes' => ServiceManager::get('Product\Attribute\AttributeRepo')->findAll(),
            'product' => $product
        ));
    }
    
    public function newAction()
    {
        $post = array();
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if (!empty($post['product']['name'])) {
                $product = ServiceManager::get('Product\ProductRepo')->create($post['product']);
                ServiceManager::get('Product\ProductRepo')->saveAttributes($product->getId(), $post['attribute']);
                $this->flashMessenger()->addSuccessMessage('Product has been saved.');
                $this->redirect()->toUrl('index');
            } else {
                $this->flashMessenger()->addErrorMessage('Empty product title detected.');
                $this->redirect()->toUrl('new');
            }
             
        }
        
        return new ViewModel(array(
            'attributes' => ServiceManager::get('Product\Attribute\AttributeRepo')->findAll(),
            'post' => $post
        ));
    }

    public function editAction()
    {
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if (!empty($post['product']['name'])) {
                $product = ServiceManager::get('Product\ProductRepo')->update($post['product']);
                ServiceManager::get('Product\ProductRepo')->saveAttributes($product->getId(), $post['attribute']);
                $this->flashMessenger()->addSuccessMessage('Product has been saved.');
                return $this->redirect()->toUrl('index');
            } else {
                $this->flashMessenger()->addErrorMessage('Empty product title detected.');
                return $this->redirect()->toUrl('index');
            }
        } else {
            $id = $this->params('id');
            if (!Helper::validateUUID($id)) {
                $this->flashMessenger()->addErrorMessage('Invalid product id: ' . $id);
                $this->redirect()->toUrl('/administration/product/index');
            }
            $product = ServiceManager::get('Product\ProductRepo')->getProductByUUID($id);
            return new ViewModel(array(
                'attributes' => ServiceManager::get('Product\Attribute\AttributeRepo')->findAll(),
                'product' => $product
            ));            
        }
    }

    public function removeAction()
    {
        $id = $this->params('id');
        if (!Helper::validateUUID($id)) {
            $this->flashMessenger()->addErrorMessage('Invalid product id: ' . $id);
            $this->redirect()->toUrl('/administration/product/index');
        }
        $product = ServiceManager::get('Product\ProductRepo')->delete($id);
        $this->flashMessenger()->addSuccessMessage('Product has been deleted.');
        return $this->redirect()->toUrl('/administration/product/index');
    }
    
    public function importAction()
    {
        
        if ($this->getRequest()->isPost()) {
            echo print_r($_FILES, true);
            exit;
        }
        
        return new ViewModel(array(
            'attributeCollection' => ServiceManager::get('Product\Attribute\AttributeRepo')->fetchAll()
        ));
    }
    
    public function importTemplateAction()
    {
        $file = new \SplFileInfo(__DIR__ . '/../../../data/import-template.csv');
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $headers = new HttpHeaders();
        $headers->addHeader(HeaderContentType::fromString('content-type: text/csv'));
        $headers->addHeader(HeaderContentLength::fromString('content-length: ' . $file->getSize()));
        $response->setHeaders($headers);
        $response->setContent(file_get_contents($file->getRealPath()));
        return $response;
    }

}
