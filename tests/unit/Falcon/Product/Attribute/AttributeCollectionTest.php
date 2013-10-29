<?php

namespace Falcon\Product\Attribute;

class AttributeCollectionTest extends \PHPUnit_Framework_TestCase
{
    
    public function testInstance()
    {
        $collection = new AttributeCollection();
        $this->assertInstanceOf('Iterator', $collection);
        $this->assertInstanceOf('Countable', $collection);
        $foo = new Attribute(1, 'Foo', Attribute::TYPE_TEXT);
        $boo = new Attribute(2, 'Boo', Attribute::TYPE_TEXT);
        $collection->add($foo);
        $collection->add($boo);
        
        $this->assertSame($foo, $collection->findByName('Foo'));
        $this->assertSame($boo, $collection->findById(2));
    }
    
}