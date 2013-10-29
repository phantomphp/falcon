<?php

namespace Falcon\Product\Import;

use Falcon\Product\ProductRepo;
use Falcon\Product\ProductRepoAwareInterface;
use Falcon\Product\Attribute\AttributeRepoAwareInterface;

class Import implements ProductRepoAwareInterface, AttributeRepoAwareInterface
{
    protected $productRepo;
    
    protected $attributeRepo;
    
    /**
     * @var SplFileInfo
     * */
    protected $csvFile;
    
    public function setProductRepo(ProductRepo $repo)
    {
        $this->productRepo = $repo;
    }
    
    public function __construct($importFile)
    {
        if (!file_exists($importFile)) {
            throw new \InvalidArgumentException('File does not exist: ' . $importFile);
        }
        $this->csvFile = new \SplFileObject($importFile, 'r');
    }
    
    public function run()
    {
        $first = true; // skip first row
        while ($row = $this->csvFile->fgetcsv()) {
            if ($first) {
                $first = false;
                $keys = $row;
                continue;
            }
            $this->processRow(array_combine($keys, $row));
        }
    }
    
    public function processRow($row)
    {
        $this->processProduct($row);
    }
    
    protected function processProduct()
    {
        
    }
    
    protected function processAttributes()
    {
        
    }
}
