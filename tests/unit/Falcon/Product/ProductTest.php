<?php

namespace Falcon\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{

    const ID = 1;
    const UUID = '01dd77c3-347e-0ade-780c-e8bded7f8d35';
    protected $data = array(
        'name' => 'Chess',
        'year' => 2013,
        'designer' => 'Rabbit',
        'publisher' => 'Williams',
        'sku' => '912321',
        'upc' => 'asadsadsa',
        'msrp' => '99.99' 
    );

    protected function getProduct()
    {
        return new Product(self::ID, self::UUID, $this->data);
    }

    public function testInstance()
    {
        $product = $this->getProduct();
        $this->assertInstanceOf('Falcon\Model\LazyModelAbstract', $product);
        $this->assertSame(self::ID, $product->getId());
        $this->assertSame(self::UUID, $product->getUUID());
        $this->assertSame($this->data['name'], $product->get('name'));
        $this->assertSame($this->data['year'], $product->get('year'));
        $this->assertSame($this->data['designer'], $product->get('designer'));
        $this->assertSame($this->data['publisher'], $product->get('publisher'));
        $this->assertSame($this->data['sku'], $product->get('sku'));
        $this->assertSame($this->data['upc'], $product->get('upc'));
        $this->assertSame($this->data['msrp'], $product->get('msrp'));
    }
    
    public function testProductDescriptions()
    {
        
    }
    
    public function testProductImages()
    {
        
    }

}