<?php

namespace Falcon\Product\Attribute;

class AttributeTest extends \PHPUnit_Framework_TestCase
{

    public function testInstance()
    {
        $id = 1;
        $label = 'Degree of freedom';
        $type = Attribute::TYPE_SET_CHECKBOX;
        $attr = new Attribute($id, $label, $type);
        $this->assertSame($id, $attr->getId());
        $this->assertSame($label, $attr->getLabel());
        $this->assertSame($type, $attr->getType());

        $value = 555;
        $attr->setValue($value);
        $this->assertSame($value, $attr->getValue());
        $this->assertEmpty($attr->hasChildren());
        $subAttr1 = new Attribute(2, 'Sub 1', Attribute::TYPE_OPTION);
        $subAttr2 = new Attribute(3, 'Sub 2', Attribute::TYPE_OPTION);
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
        $topAttr = new Attribute(1, 'Funny', -1);
    }
}