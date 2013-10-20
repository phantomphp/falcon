<?php

namespace Falcon\Product;

use Falcon\ServiceManagerProviderTestCase;

class ProductAttributeRepoTest extends ServiceManagerProviderTestCase
{
    public function getRepo()
    {
        return $this->get('Product\ProductAttributeRepo');
    }
    
    public function testInstance()
    {
        $repo = $this->getRepo();
        $this->assertInstanceOf('Falcon\Product\ProductAttributeRepo', $repo);
    }
    
    public function testCategoryAssembly()
    {
        $data = array(
            'id' => 1,
            'label' => 'Category',
            'type' => ProductAttribute::TYPE_SELECT,
            'options' => '["1a","1b","1c"]',
        );
        $expected = new ProductAttribute($data['id'], $data['label'], $data['type']);
        $expected->setOptions(json_decode($data['options']));
        $actual = $this->getRepo()->assembleAttribute($data);
        $this->assertEquals($expected, $actual);
    }
    
    public function testWeightAssembly()
    {
        $data = array(
            'id' => '20',
            'label' => 'Weight',
            'type' => ProductAttribute::TYPE_SET,
            'options' => null,
            'children' => array(
                array(
                    'id' => '21',
                    'label' => 'Light',
                    'type' => ProductAttribute::TYPE_CHECKBOX
                ),
                array(
                    'id' => '22',
                    'label' => 'Medium',
                    'type' => ProductAttribute::TYPE_CHECKBOX
                ),
                array(
                    'id' => '23',
                    'label' => 'Heavy',
                    'type' => ProductAttribute::TYPE_CHECKBOX,
                    'value' => 1
                    
                ),
            )
        );
        $expected = new ProductAttribute($data['id'], $data['label'], $data['type']);
        foreach ($data['children'] as $child) {
            $attr = new ProductAttribute($child['id'], $child['label'], $child['type']);
            if (isset($child['value'])) {
                $attr->setValue($child['value']);
            }
            $expected->addChild($attr);
        }
        $actual = $this->getRepo()->assembleAttribute($data);
        $this->assertEquals($expected, $actual);
    }
    
    public function testFindAll()
    {
        $attribute = new ProductAttribute(30, 'Ages', ProductAttribute::TYPE_SET);
        $attribute->addChild(new ProductAttribute(35, '15+', ProductAttribute::TYPE_CHECKBOX));
        $attribute->addChild(new ProductAttribute(36, 'Adult', ProductAttribute::TYPE_CHECKBOX));
        $actual = $this->getRepo()->findAll();
        $this->assertEquals(array( 30 => $attribute), $actual);
    }
}