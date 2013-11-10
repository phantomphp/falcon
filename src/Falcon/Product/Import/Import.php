<?php

namespace Falcon\Product\Import;

use Falcon\Product\Product;
use Falcon\Product\ProductRepo;
use Falcon\Product\ProductRepoAwareInterface;
use Falcon\Product\Attribute\Attribute;
use Falcon\Product\Attribute\AttributeRepo;
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
    
    public function setAttributeRepo(AttributeRepo $repo)
    {
        $this->attributeRepo = $repo;
    }
    
    public function __construct($importFile = null)
    {
        if (!empty($importFile)) {
            if (!file_exists($importFile)) {
                throw new \InvalidArgumentException('File does not exist: ' . $importFile);
            }
            $this->csvFile = new \SplFileObject($importFile, 'r');
        }
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
        $product = $this->processProduct($row);
        $this->processAttributes($product->getId(), $row);
    }
    
    protected function processProduct($row)
    {
        $mainData = array(
            'name' => $row['name'],
            'year' => $row['year'],
            'designer' => $row['designer'],
            'publisher' => $row['publisher'],
            'sku' => $row['sku'],
            'upc' => $row['upc'],
            'msrp' => $row['msrp']
        );
        try {
            $product = $this->productRepo->getProductBySKU($row['sku']);
            foreach ($mainData as $key => $val) {
                $product->set($key, $val);
            }
        } catch(\Exception $e) {
            $product = new Product(NULL, NULL, $mainData);
        }
        return $this->productRepo->save($product); 
    }
    
    protected function processAttributes($productId, $row)
    {
        $attributeNames = array(
            'Category',  
            'Players',
            'Weight',
            'Ages',
            'Duration',
            'Theme',
            'Theme Style',
            'Interaction',
            'Win Condition',
            'Skills',
            'Pace',
            'Reading Level',
            'Description'
        );
        $attributes = array();
        $attributeCollection = $this->attributeRepo->fetchAll();
        foreach ($attributeNames as $name) {
            if (empty($row[$name])) {
                continue;
            }
            $attribute = $attributeCollection->findByName($name);
            switch ($attribute->getType()) {
                case Attribute::TYPE_SET:
                    $childNames = explode(',', $row[$name]);
                    foreach ($childNames as $childName) {
                        $child = $attributeCollection->findByName($childName);
                        if ($child->getType() == Attribute::TYPE_RADIO) {
                            $attributes[$attribute->getId()] = $child->getId();
                        } elseif ($child->getType() == Attribute::TYPE_CHECKBOX) {
                            $attributes[$child->getId()] = 1;
                        }
                    }
                    break;
                default:
                    $attributes[$attribute->getId()] = $row[$name];
            }
        }
        $this->productRepo->saveAttributes($productId, $attributes);
    }
}
