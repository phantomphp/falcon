<?php

namespace Falcon\Product;

use Falcon\ServiceManagerProviderTestCase;
use Falcon\Helper\Helper;

class ProductRepoTest extends ServiceManagerProviderTestCase
{

    const ID = 1;
    const UUID = '424422df-13cd-11e3-b252-080027150945';
    const NAME = 'Chess';
    const ACTIVE = 1;
    const DELETED = 0;
    const CREATED_DATE = '2013-09-01 23:00:00';
    const MODIFIED_DATE = '2013-09-02 19:00:00';

    public function setUp()
    {
        parent::setUp();
        $this->getDb()->execute('truncate product');
        $this->createProductRecord();
    }

    public function tearDown()
    {
        $this->getDb()->execute('truncate product');
    }

    protected function getRepo()
    {
        return new ProductRepo();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Falcon\Product\ProductRepo', $this->getRepo());
    }

    /**
     * @expectedException Falcon\Exception\RecordNotFoundException
    */
    public function testGetProductByIdThrowsExceptionWhenProductIsNotFound()
    {
        $repo = $this->get('Product\ProductRepo');
        $repo->getProductById(99);
    }

    protected function createProductRecord()
    {
        $data = array(
            'id' => self::ID,
            'uuid' => self::UUID,
            'name' => self::NAME,
            'active' => self::ACTIVE,
            'deleted' => self::DELETED,
            'created_date' => self::CREATED_DATE,
            'modified_date' => self::MODIFIED_DATE
        );
        $this->getDb()->insert('product', $data);

    }

    public function testGetProductById()
    {
        $repo = $this->get('Product\ProductRepo');
        $expected = new Product(self::ID, self::UUID, self::NAME);
        $expected->setActive(self::ACTIVE);
        $expected->setDeleted(self::DELETED);
        $expected->setCreatedDate(self::CREATED_DATE);
        $expected->setModifiedDate(self::MODIFIED_DATE);

        $actual = $repo->getProductById(self::ID);
        $this->assertEquals($expected, $actual);
    }

    public function testCreateProduct()
    {
        $repo = $this->get('Product\ProductRepo');
        $name = 'kinder';
        $product = $repo->create($name);
        $this->assertSame($name, $product->getName());
        $this->assertTrue($product->isActive());
    }

    public function testUpdateProduct()
    {
        $repo = $this->get('Product\ProductRepo');
        $product = $repo->getProductById(self::ID);
        $product->setName('Monopoly');
        $repo->update($product);
        $actual = $repo->getProductById(self::ID);
        $this->assertEquals($product, $actual);
    }
}