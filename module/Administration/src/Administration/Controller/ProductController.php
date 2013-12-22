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
use Falcon\Product\Attribute\Attribute;

use Zend\View\Model\ViewModel;
use Zend\Http\Headers as HttpHeaders;
use Zend\Http\Header\ContentType as HeaderContentType;
use Zend\Http\Header\ContentLength as HeaderContentLength;

class ProductController extends SecureController
{
    
    private $attribute_map = array(
        Attribute::TYPE_TEXT => 'Text',
        Attribute::TYPE_TEXTAREA => 'Textarea',
        Attribute::TYPE_CHECKBOX => 'Checkbox',
        Attribute::TYPE_URL => 'URL',
        Attribute::TYPE_SELECT => 'Select',
        Attribute::TYPE_SET_RADIO => 'Radio Set',
        Attribute::TYPE_SET_CHECKBOX => 'Checkbox Set',
    );
    
    public function indexAction()
    {
        return new ViewModel(array(
            'products' => ServiceManager::get('Product\ProductRepo')->findAll(),
            'attributes' => ServiceManager::get('Product\Attribute\AttributeRepo')->findAll(),
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
            $error = '';
            $result = '';
            try {
                $file = $_FILES['file']['tmp_name'];
                $import = ServiceManager::get('Product\Import\Import');
                $import->setFile($file);
                $result = $import->run();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
            echo json_encode(array('result' => $result, 'error' => $error));
            exit;
        }
        
        return new ViewModel(array(
            'attributeCollection' => ServiceManager::get('Product\Attribute\AttributeRepo')->fetchAll(),
            'attribute_map' => $this->attribute_map
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

    public function imageUploadAction()
    {
        
    }
    
    public function attributeManagerAction()
    {
        
    }

}
