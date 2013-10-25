<?php

namespace Falcon\Product;

use Falcon\Repository\RepositoryAwareInterface;
use Falcon\Repository\RepositoryInterface;
use Falcon\Exception\RecordNotFoundException;

class ProductRepo implements RepositoryAwareInterface
{
    protected $repository;

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getProductById($id)
    {
        return $this->find(array('id' => $id));
    }

    public function getProductByUUID($uuid)
    {
        return $this->find(array('uuid' => $uuid));
    }
    
    public function getProductBySKU($sku)
    {
        return $this->find(array('sku' => $sku));
    }

    public function create($data)
    {
        $product = new Product(NULL, NULL, $data);
        $product->setCreatedDate('now');
        $product->setModifiedDate('now');
        if ($this->save($product)) {
            return $product;
        }
        throw new \RuntimeException('Cannot create a product!');
    }

    public function update(array $data)
    {
        $product = new Product($data['id'], $data['uuid'], $data);
        $product->setModifiedDate('now');
        return $this->save($product);
    }

    protected function find(array $params)
    {
        $products = $this->findAll($params);
        if (empty($products)) {
            throw new RecordNotFoundException('Product does not exist!');
        }
        
        return array_pop($products);
    }
    
    public function findAll($params = array())
    {
        $products = array();
        $params['deleted'] = 0;
        $result = $this->repository->select('product', array('*'), $params);
        foreach ($result as $row) {
            $product = new Product($row['id'], $row['uuid'], array(
                'name' => $row['name'],
                'year' => $row['year'],
                'designer' => $row['designer'],
                'publisher' => $row['publisher'],
                'sku' => $row['sku'],
                'upc' => $row['upc'],
                'msrp' => $row['msrp'],
            ));
            $product->setActive($row['active']);
            $product->setDeleted($row['deleted']);
            $product->setCreatedDate($row['created_date']);
            $product->setModifiedDate($row['modified_date']);
            $products[] = $this->populateAttributes($product);
        }

        return $products;        
    }
    
    protected function populateAttributes(Product $product)
    {
        $result = $this->repository->select('product_attribute', array('*'), array('product_id' => $product->getId()));
        foreach ($result as $record) {
            if ($record['intval']) {
                $val = (int) $record['intval'];
            } else {
                $val = $record['textval'];
            }
            $product->setAttribute($record['attribute_id'], $val);
        }
        
        return $product;
    }

    public function save(Product $product)
    {
        try {
            $data = array(
                'id' => $product->getId(),
                'uuid' => $product->getUUID(),
                'name' => $product->get('name'),
                'year' => $product->get('year'),
                'designer' => $product->get('designer'),
                'publisher' => $product->get('publisher'),
                'sku' => $product->get('sku'),
                'upc' => $product->get('upc'),
                'msrp' => $product->get('msrp'),
                'active' => (int)$product->isActive(),
                'deleted' => (int)$product->isDeleted(),
                'modified_date' => $product->getModifiedDate()
            );
            if ($product->getCreatedDate()) {
                $data['created_date'] = $product->getCreatedDate();
            }
            if (!$product->getId()) {
                $id = $this->repository->insert('product', $data);
                $product->setId($id);
            } else {
                $this->repository->update('product', $data, array('id' => $product->getId()));
            }
            
        } catch (Exception $e) {
            return false;
        }

        return $product;
    }
    
    public function saveAttributes($productId, $attributes)
    {
        $this->repository->delete('product_attribute', array('product_id' => $productId));
        foreach ($attributes as $key => $val) {
            $data = array();
            if (is_numeric($val)) {
                $data['intval'] = $val;
            } else {
                $data['textval'] = $val;
            }
            $data['attribute_id'] = $key;
            $data['product_id'] = $productId;
            $this->repository->insert('product_attribute', $data);
        }
    }

    public function delete($uuid)
    {
        return $this->repository->update('product', array('deleted' => 1), array('uuid' => $uuid));
    }
    
}