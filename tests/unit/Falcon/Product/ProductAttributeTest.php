<?php

namespace Falcon\Product;

class ProductAttributeTest extends \PHPUnit_Framework_TestCase
{
    
    public function testInstance()
    {
        $id = 1;
        $label = 'Degree of freedom';
        $type = ProductAttribute::TYPE_SET;
        $attr = new ProductAttribute($id, $label, $type);
        $this->assertSame($id, $attr->getId());
        $this->assertSame($label, $attr->getLabel());
        $this->assertSame($type, $attr->getType());
        $options = array(
            array('1' => '2011'),
            array('2' => '2012'),
        );
        $attr->setOptions($options);
        $this->assertSame($options, $attr->getOptions());
        $value = 555;
        $attr->setValue($value);
        $this->assertSame($value, $attr->getValue());
        $this->assertEmpty($attr->hasChildren());
        $subAttr1 = new ProductAttribute(2, 'Sub 1', ProductAttribute::TYPE_TEXT);
        $subAttr2 = new ProductAttribute(3, 'Sub 2', ProductAttribute::TYPE_RADIO);
        $attr->addChild($subAttr1);
        $attr->addChild($subAttr2);
        $this->assertTrue($attr->hasChildren());
        $this->assertEquals(array($subAttr1, $subAttr2), $attr->getChildren());
    }
    
    /**
     * @expectedException InvalidArgumentException
     * */
    public function testInvalidTypeThrowsException()
    {
        $topAttr = new ProductAttribute(1, 'Funny', -1); 
    }
}