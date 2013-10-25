<?php

namespace Falcon\Product\Import;

use Falcon\Product\ProductRepo;
use Falcon\Product\ProductRepoAwareInterface;

class Import implements ProductRepoAwareInterface
{
    protected $productRepo;
    
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
                continue;
            }
            $this->processRow($row);
        }
    }
    
    public function processRow($row)
    {
        
    }
    
    protected function processProduct()
    {
        
    }
    
    protected function processAttributes()
    {
        
    }
}
