<?php

namespace Falcon\Product\Import;

use Falcon\ServiceManagerProviderTestCase;

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
            'Category' => '1c',
            'Players' => '2',
            'Weight' => 'Light,Heavy',
            'Ages' => '2-4,5-7',
            'Duration' => '10-20 min',
            'Theme' => 'Educational',
            'Theme Style' => 'None',
            'Interaction' => 'Hostile',
            'Win Condition' => '',
            'Skills' => '',
            'Pace' => '',
            'Reading Level' => '',
            'Description' => ''
        );
    }
    
    public function testProcessProduct()
    {
        
    }
} 