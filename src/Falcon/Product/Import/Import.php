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
    
    protected $columns;
    
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
            $this->setFile($importFile);
        }
    }
    
    public function setFile($importFile)
    {
        if (!file_exists($importFile)) {
            throw new \InvalidArgumentException('File does not exist: ' . $importFile);
        }
        $this->csvFile = new \SplFileObject($importFile, 'r');
    }
    
    public function run()
    {
        $first = true; // skip first row
        $result = 0;
        while ($row = $this->csvFile->fgetcsv()) {
            if ($first) {
                $first = false;
                $keys = $this->columns = $row;
                continue;
            }
            $this->processRow(array_combine($keys, $row));
            $result++;
        }
        
        return $result;
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
        $row = array_slice($row, 7, NULL, true);
        $attributes = array();
        $attributeCollection = $this->attributeRepo->fetchAll();
        foreach ($row as $attributeName => $values) {
            if (empty($values)) {
                continue;
            }
            try {
                $attribute = $attributeCollection->findByName($attributeName);
            } catch (\Exception $e) {
                continue;
            }
            switch ($attribute->getType()) {
                case Attribute::TYPE_SELECT:
                case Attribute::TYPE_SET_RADIO:    
                    $child = $attributeCollection->findByName($values);
                    $attributes[$attribute->getId()] = $child->getId();
                    break;
                case Attribute::TYPE_SET_CHECKBOX:
                    $childNames = explode(',', $values);
                    foreach ($childNames as $childName) {
                        $child = $attributeCollection->findByName($childName);
                        $attributes[$child->getId()] = 1;
                    }
                    break;
                case Attribute::TYPE_CHECKBOX:
                    $attributes[$attribute->getId()] = (int) $values;
                    break;
                default:
                    $attributes[$attribute->getId()] = $values;
            }
        }
        $this->productRepo->saveAttributes($productId, $attributes);
    }
}
