<?php

namespace Falcon\Product\Import;

use Falcon\ServiceManagerProviderTestCase;
use Falcon\Product\Attribute\Attribute;

class ImportTest extends ServiceManagerProviderTestCase
{
    public function getImport()
    {
        return $this->get('Product\Import\Import');
    }

    public function testInstance()
    {
        $import = $this->getImport();
        $this->assertInstanceOf('Falcon\Product\Import\Import', $import);
        $this->assertInstanceOf('Falcon\Product\ProductRepoAwareInterface', $import);
    }
    
    protected function getTestRow()
    {
        return array(
            'sku' => 'sku-1',
            'name' => 'name-2',
            'year' => '2013',
            'designer' => 'designer-4',
            'publisher' => 'publisher-5',
            'upc' => 'upc-6',
            'msrp' => 'msrp-7',
            'Category' => '1b',
            'Difficulty' => 'Hard',
            'Theme' => 'Some theme',
            'Description' => 'sadjslkadj askld ja',
            'Active' => '1',
            'URL' => 'http://google.com',
            'Strategy' => 'Think,Shoot'
        );
    }
    
    public function testProcessRow()
    {
        $import = $this->getImport();
        $productRepo = $this->get('Product\ProductRepo');
        $attributeRepo = $this->get('Product\Attribute\AttributeRepo');
        $row = $this->getTestRow();
        $import->processRow($row);
        $product = $productRepo->getProductBySKU($row['sku']);
        $this->assertNotEmpty($product);
        $attributeCollection = $attributeRepo->fetchAll();
        # SELECT
        $parent = $attributeCollection->findByName('Category');
        $child = $attributeCollection->findByName('1b');
        $this->assertSame($product->getAttribute($parent->getId()), $child->getId());
    }

    public function tearDown()
    {
        $this->getDb()->execute('truncate product');
        $this->getDb()->execute('truncate product_attribute');
    }
} 