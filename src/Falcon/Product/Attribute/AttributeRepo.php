<?php

namespace Falcon\Product\Attribute;

use Falcon\Repository\RepositoryAwareInterface;
use Falcon\Repository\RepositoryInterface;

class AttributeRepo implements RepositoryAwareInterface
{
    protected $repository;
    
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function assembleAttribute($data)
    {
        $attr = new Attribute($data['id'], $data['label'], $data['type']);
        switch($attr->getType()) {
            case Attribute::TYPE_CHECKBOX:
                break;
            case Attribute::TYPE_SELECT:
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
        return $this->fetchAll();
    }
    
    public function fetchAll()
    {
        $data = array();
        $attributes =  new AttributeCollection;
        $result = $this->repository->select('attribute', array('*'));
        foreach ($result as $record) {
            $parentId = $record['parent_id'];
            unset($record['parent_id']);
            if (!empty($parentId)) {
                $data[$parentId]['children'][] = $record;
            } else {
                $data[$record['id']] = $record;
            }
        }
        
        foreach ($data as $attr) {
            $attributes->add($this->assembleAttribute($attr));
        }

        return $attributes;
    }
    
}
