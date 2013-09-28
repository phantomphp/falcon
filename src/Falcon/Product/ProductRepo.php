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

    public function create($name)
    {
        $product = new Product(NULL, NULL, $name);
        $product->setCreatedDate('now');
        $product->setModifiedDate('now');
        if ($this->save($product)) {
            return $product;
        }
        throw new \RuntimeException('Cannot create a product!');
    }

    public function update(Product $product)
    {
        return $this->save($product);
    }

    protected function find(array $params)
    {
        $result = $this->repository->select('product', array('*'), $params);
        if (!$result->valid()) {
            throw new RecordNotFoundException('Product does not exist!');
        }
        $row = $result->current();
        $product = new Product($row['id'], $row['uuid'], $row['name']);
        $product->setActive($row['active']);
        $product->setDeleted($row['deleted']);
        $product->setCreatedDate($row['created_date']);
        $product->setModifiedDate($row['modified_date']);

        return $product;
    }

    protected function save(Product $product)
    {
        try {
            $data = array(
                'id' => $product->getId(),
                'uuid' => $product->getUUID(),
                'name' => $product->getName(),
                'active' => (int)$product->isActive(),
                'deleted' => (int)$product->isDeleted(),
                'created_date' => $product->getCreatedDate(),
                'modified_date' => $product->getModifiedDate()
            );

            $this->repository->delete('product', array('id' => $product->getId()));
            $this->repository->insert('product', $data);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}