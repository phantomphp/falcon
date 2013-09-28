<?php

namespace Falcon\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{

    const ID = 1;
    const UUID = '01dd77c3-347e-0ade-780c-e8bded7f8d35';
    const NAME = 'Chess';

    protected function getProduct()
    {
        return new Product(self::ID, self::UUID, self::NAME);
    }

    public function testInstance()
    {
        $product = $this->getProduct();
        $this->assertInstanceOf('Falcon\Model\ModelAbstract', $product);
        $this->assertSame(self::ID, $product->getId());
        $this->assertSame(self::NAME, $product->getName());
        $product->setName('Monopoly');
        $this->assertSame('Monopoly', $product->getName());
    }

}