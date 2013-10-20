<?php

namespace Falcon\Product;

use Falcon\Repository\RepositoryAwareInterface;
use Falcon\Repository\RepositoryInterface;

class ProductAttributeRepo implements RepositoryAwareInterface
{
    protected $repository;
    
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function assembleAttribute($data)
    {
        $attr = new ProductAttribute($data['id'], $data['label'], $data['type']);
        switch($attr->getType()) {
            case ProductAttribute::TYPE_CHECKBOX:
                break;
            case ProductAttribute::TYPE_SELECT:
                $options = json_decode($data['options']);
                $attr->setOptions($options);
                break;
        }
        if (isset($data['children']) && !empty($data['children'])) {
            foreach ($data['children'] as $child) {
                $attr->addChild($this->assembleAttribute($child));
            }
        }
        if (isset($data['value']) && $data['value'] !== '') {
            $attr->setValue($data['value']);
        }
        return $attr;
    }
    
    public function findAll()
    {
        $data = $attributes = array();
        $result = $this->repository->select('attribute', array('*'));
        while ($result->valid()) {
            $record = $result->current();
            $parentId = $record['parent_id'];
            unset($record['parent_id']);
            if (!empty($parentId)) {
                $data[$parentId]['children'][] = $record;
            } else {
                $data[$record['id']] = $record;
            }
            $result->next();
        }
        
        foreach ($data as $attr) {
            $attributes[$attr['id']] = $this->assembleAttribute($attr);
        }

        return $attributes;
    }
}
