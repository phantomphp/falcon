<?php

namespace Falcon\Product\Attribute;

use Falcon\ServiceManagerProviderTestCase;

class AttributeRepoTest extends ServiceManagerProviderTestCase
{
    public function getRepo()
    {
        return $this->get('Product\Attribute\AttributeRepo');
    }

    public function testInstance()
    {
        $repo = $this->getRepo();
        $this->assertInstanceOf('Falcon\Product\Attribute\AttributeRepo', $repo);
    }

    public function testWeightAssembly()
    {
        $data = array(
            'id' => '20',
            'label' => 'Weight',
            'type' => Attribute::TYPE_SET_CHECKBOX,
            'options' => null,
            'children' => array(
                array(
                    'id' => '21',
                    'label' => 'Light',
                    'type' => Attribute::TYPE_OPTION
                ),
                array(
                    'id' => '22',
                    'label' => 'Medium',
                    'type' => Attribute::TYPE_OPTION
                ),
                array(
                    'id' => '23',
                    'label' => 'Heavy',
                    'type' => Attribute::TYPE_OPTION,
                    'value' => 1

                ),
            )
        );
        $expected = new Attribute($data['id'], $data['label'], $data['type']);
        foreach ($data['children'] as $child) {
            $attr = new Attribute($child['id'], $child['label'], $child['type']);
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
        $attribute = new Attribute(30, 'Ages', Attribute::TYPE_SET_CHECKBOX);
        $attribute->addChild(new Attribute(35, '15+', Attribute::TYPE_OPTION));
        $attribute->addChild(new Attribute(36, 'Adult', Attribute::TYPE_OPTION));
        $actual = $this->getRepo()->findAll();
        $collection = new AttributeCollection();
        $collection->add($attribute);
        $this->assertEquals($collection, $actual);
    }
}