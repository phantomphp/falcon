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

use Falcon\Product\Attribute\Attribute;

class AttributeController extends SecureController
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
            'attributes' => ServiceManager::get('Product\Attribute\AttributeRepo')->findAll(),
            'attribute_map' => $this->attribute_map
        ));
    }
    
    public function newAction()
    {
        $this->capturePostAndSave('new');
        return new ViewModel(array(
            'attribute_map' => $this->attribute_map,
            'setTypes' => $this->setTypes,
        ));
    }
    
    public function editAction()
    {
        $this->capturePostAndSave('edit');
        $id = $this->params('id');
        $collection = ServiceManager::get('Product\Attribute\AttributeRepo')->fetchAll();
        $attribute = $collection->findById($id);
        $options = array();
        return new ViewModel(array(
            'attribute_map' => $this->attribute_map,
            'setTypes' => $this->setTypes,
            'attribute' => $attribute
        ));
    }
    
    public function removeAction()
    {
        $id = $this->params('id');
        ServiceManager::get('Product\Attribute\AttributeRepo')->deleteAttribute($id);
        $this->flashMessenger()->addSuccessMessage('Attribute has been removed.');
        return  $this->redirect()->toUrl('/administration/attribute/index');
    }
    
    protected function capturePostAndSave($redirectUrl = 'index')
    {
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if (!empty($post['attribute']['label'])) {
                $product = ServiceManager::get('Product\Attribute\AttributeRepo')->save($post['attribute']);
                $this->flashMessenger()->addSuccessMessage('Attribute has been saved.');
                $this->redirect()->toUrl('/administration/attribute/index');
            } else {
                $this->flashMessenger()->addErrorMessage('Attribute name cannot be empty');
                $this->redirect()->toUrl('/administration/attribute/' . $redirectUrl);
            }
        }
    }
    
}