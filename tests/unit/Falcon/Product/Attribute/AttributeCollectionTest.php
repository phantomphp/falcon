<?php

namespace Falcon\Product\Attribute;

class AttributeCollectionTest extends \PHPUnit_Framework_TestCase
{
    
    public function testInstance()
    {
        $collection = new AttributeCollection();
        $this->assertInstanceOf('Iterator', $collection);
        $this->assertInstanceOf('Countable', $collection);
        $collection->add(new Attribute(1, 'Foo', Attribute::TYPE_TEXT));
        $collection->add(new Attribute(2, 'Foo', Attribute::TYPE_TEXT));
        $this->assertSame(2, count($collection));
    }
    
}